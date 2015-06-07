<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */

namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Abstract class with common functionality for all controllers.
 *
 * @package TYPO3_Aimeos
 */
abstract class AbstractController
	extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	private $_context;
	private static $_locale;


	/**
	 * Creates a new configuration object.
	 *
	 * @return MW_Config_Interface Configuration object
	 */
	protected function _getConfig()
	{
		$settings = (array) $this->settings;

		if( isset( $this->settings['typo3']['tsconfig'] ) )
		{
			$tsconfig = Base::parseTS( $this->settings['typo3']['tsconfig'] );
			$settings = \TYPO3\CMS\Extbase\Utility\ArrayUtility::arrayMergeRecursiveOverrule( $settings, $tsconfig );
		}

		return Base::getConfig( $settings );
	}


	/**
	 * Returns the context item
	 *
	 * @return \MShop_Context_Item_Interface Context item
	 */
	protected function _getContext()
	{
		if( !isset( $this->_context ) )
		{
			$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
			$config = $this->_getConfig( $this->settings );
			$context = Base::getContext( $config );

			$localI18n = ( isset( $this->settings['i18n'] ) ? $this->settings['i18n'] : array() );
			$locale = $this->_getLocale( $context );
			$langid = $locale->getLanguageId();

			$context->setLocale( $locale );
			$context->setI18n( Base::getI18n( array( $langid ), $localI18n ) );
			$context->setView( Base::getView( $config, $this->uriBuilder, $templatePaths, $this->request, $langid ) );

			if( TYPO3_MODE === 'FE' && $GLOBALS['TSFE']->loginUser == 1 )
			{
				$context->setEditor( $GLOBALS['TSFE']->fe_user->user['username'] );
				$context->setUserId( $GLOBALS['TSFE']->fe_user->user[$GLOBALS['TSFE']->fe_user->userid_column] );
			}

			$this->_context = $context;
		}

		return $this->_context;
	}


	/**
	 * Returns the locale object for the context
	 *
	 * @param \MShop_Context_Item_Interface $context Context object
	 * @return \MShop_Locale_Item_Interface Locale item object
	 */
	protected function _getLocale( \MShop_Context_Item_Interface $context )
	{
		if( !isset( self::$_locale ) )
		{
			$session = $context->getSession();
			$config = $context->getConfig();


			$sitecode = $config->get( 'mshop/locale/site', 'default' );
			$name = $config->get( 'typo3/param/name/site', 'loc-site' );

			if( $this->request->hasArgument( $name ) === true ) {
				$sitecode = $this->request->getArgument( $name );
			}


			$langid = $config->get( 'mshop/locale/language', '' );
			$name = $config->get( 'typo3/param/name/language', 'loc-language' );

			if( isset( $GLOBALS['TSFE']->config['config']['language'] ) ) {
				$langid = $GLOBALS['TSFE']->config['config']['language'];
			}

			if( $this->request->hasArgument( $name ) === true ) {
				$langid = $this->request->getArgument( $name );
			}


			$currency = $config->get( 'mshop/locale/currency', '' );
			$name = $config->get( 'typo3/param/name/currency', 'loc-currency' );

			if( $this->request->hasArgument( $name ) === true ) {
				$currency = $this->request->getArgument( $name );
			}


			$localeManager = \MShop_Locale_Manager_Factory::createManager( $context );
			self::$_locale = $localeManager->bootstrap( $sitecode, $langid, $currency );
		}

		return self::$_locale;
	}


	/**
	 * Returns the output of the client and adds the header.
	 *
	 * @param Client_Html_Interface $client Html client object
	 * @return string HTML code for inserting into the HTML body
	 */
	protected function _getClientOutput( \Client_Html_Interface $client )
	{
		$client->setView( $this->_getContext()->getView() );
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
	}


	/**
	 * Disables Fluid views for performance reasons.
	 *
	 * return Tx_Extbase_MVC_View_ViewInterface View object
	 */
	protected function resolveView()
	{
		return null;
	}
}
