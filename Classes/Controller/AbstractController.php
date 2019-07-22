<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Abstract class with common functionality for all controllers.
 *
 * @package TYPO3
 */
abstract class AbstractController
	extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	private static $aimeos;
	private static $context;
	private $contextBE;


	/**
	 * Creates a new configuration object.
	 *
	 * @return \Aimeos\MW\Config\Iface Configuration object
	 * @deprecated Use \Aimeos\Aimeos\Base::getConfig() directly
	 */
	protected function getConfig()
	{
		return Base::getConfig( (array) $this->settings );
	}


	/**
	 * Returns the context item for the frontend
	 *
	 * @param string $templatePath Path for retrieving the template paths used in the view
	 * @param boolean $withView True to add view to context object, false for no view
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContext( $templatePath = 'client/html/templates', $withView = true )
	{
		$config = Base::getConfig( (array) $this->settings );

		if( !isset( self::$context ) )
		{
			$context = Base::getContext( $config );
			$locale = Base::getLocale( $context, $this->request );
			$context->setI18n( Base::getI18n( array( $locale->getLanguageId() ), $config->get( 'i18n', array() ) ) );
			$context->setLocale( $locale );

			self::$context = $context;
		}

		// Use plugin specific configuration
		self::$context->setConfig( $config );

		$this->assignFrontendTime();

		if( $withView === true )
		{
			$langid = self::$context->getLocale()->getLanguageId();
			$paths = self::$aimeos->getCustomPaths( $templatePath );
			$view = Base::getView( self::$context, $this->uriBuilder, $paths, $this->request, $langid );

			self::$context->setView( $view );
		}

		return self::$context;
	}


	/**
	 * Assigning the frontend time to the context, if necessary.
	 */
	protected function assignFrontendTime()
	{
		// Handle the admin panel, according to the version number.
		if ( version_compare( TYPO3_version, '9.3.99', '<=' ) ) {
			// The old admin panel saves it's stuff inside the user settings of
			// the current admin user. These settings will get used, even if the
			// actual panel is deactivated in the site template.
			if ( $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['display_top'] === '1'
				&& !empty( (int)$GLOBALS['BE_USER']->uc['TSFE_adminConfig']['preview_simulateDate'] ) )
			{
				self::$context->setDateTime(
					date( 'Y-m-d H:i:s', (int) $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['preview_simulateDate'] )
				);
				// Early return. The admin settings overwrite the TS settings.
				return;
			}
		}
		elseif ( isset( $GLOBALS['BE_USER']->adminPanel ) )
		{
			// Read the actual configuration and then use it.
			$time = $this->objectManager->get( \TYPO3\CMS\Adminpanel\Service\ConfigurationService::class )
				->getConfigurationOption( 'preview', 'simulateDate' );
			if ( !empty( $time ) ) {
				self::$context->setDateTime(
					date( 'Y-m-d H:i:s', strtotime($time) )
				);
				// Early return.
				return;
			}
		}

		// Check the current user groups.
		if ( isset( $GLOBALS['TSFE']->fe_user->user['usergroup'] ) )
		{
			$groupIds = explode( ',', $GLOBALS['TSFE']->fe_user->user['usergroup'] );
			$feUserGroup = $this->settings['aitime']['feusergroup'];
			if ( in_array( $feUserGroup, $groupIds, true ) )
			{
				// Simulate the new time . . .
				self::$context->setDateTime(
					date( 'Y-m-d H:i:s', (int) $this->settings['aitime']['timestamp'] )
				);
			}
		}
	}


	/**
	 * Returns the context item for backend operations
	 *
	 * @param array $templatePaths List of paths to the view templates
	 * @param boolean $withView True to add view to context object, false for no view
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContextBackend( $templatePath = 'admin/jqadm/templates', $withView = true )
	{
		if( !isset( $this->contextBE ) )
		{
			$lang = 'en';
			$site = 'default';

			if( isset( $GLOBALS['BE_USER']->uc['lang'] ) && $GLOBALS['BE_USER']->uc['lang'] != '' ) {
				$lang = $GLOBALS['BE_USER']->uc['lang'];
			}

			if( $this->request->hasArgument( 'lang' )
				&& ( $value = $this->request->getArgument( 'lang' ) ) != ''
			) {
				$lang = $value;
			}

			if( $this->request->hasArgument( 'site' )
				&& ( $value = $this->request->getArgument( 'site' ) ) != ''
			) {
				$site = $value;
			}

			$config = Base::getConfig( (array) $this->settings );
			$context = Base::getContext( $config );

			$locale = Base::getLocaleBackend( $context, $site );
			$context->setLocale( $locale );

			$i18n = Base::getI18n( array( $lang, 'en' ), $config->get( 'i18n', array() ) );
			$context->setI18n( $i18n );

			if( $withView )
			{
				$paths = self::$aimeos->getCustomPaths( $templatePath );
				$view = Base::getView( $context, $this->uriBuilder, $paths, $this->request, $lang, false );
				$context->setView( $view );
			}

			$this->contextBE = $context;
		}

		return $this->contextBE;
	}


	/**
	 * Returns the locale object for the context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object (no type hint to prevent reflection)
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 * @deprecated Use \Aimeos\Aimeos\Base::getLocale() directly
	 */
	protected function getLocale( $context )
	{
		return Base::getLocale( $context, $this->request );
	}


	/**
	 * Returns the output of the client and adds the header.
	 *
	 * @param \Aimeos\Client\Html\Iface $client Html client object (no type hint to prevent reflection)
	 * @return string HTML code for inserting into the HTML body
	 */
	protected function getClientOutput( $client )
	{
		$client->setView( $this->getContext()->getView() );
		$client->process();

		$this->response->addAdditionalHeaderData( (string) $client->getHeader() );

		return $client->getBody();
	}


	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$this->uriBuilder->setArgumentPrefix( 'ai' );

		// initialize bootstrapping
		self::$aimeos = Base::getAimeos();
	}


	/**
	 * Disables Fluid views for performance reasons
	 *
	 * return null
	 */
	protected function resolveView()
	{
		return null;
	}
}
