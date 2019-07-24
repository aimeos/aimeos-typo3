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
