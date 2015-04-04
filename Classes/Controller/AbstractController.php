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
	private $_aimeos;
	static private $_locale;
	static private $_context;
	static private $_i18n = array();


	public function __construct()
	{
		parent::__construct();

		$this->_aimeos = Base::getAimeos();
	}


	/**
	 * Initializes the object before the real action is called.
	 */
	protected function initializeAction()
	{
		$context = $this->_getContext();
		$locale = $this->_getLocale( $context );

		$context->setLocale( $locale );
		$context->setCache( $this->_getCache( $context, $locale->getSiteId() ) );
		$context->setI18n( $this->_getI18n( array( $locale->getLanguageId() ) ) );

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


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @return MW_View_Interface View object
	 */
	protected function _createView()
	{
		$context = $this->_getContext();
		$config = $context->getConfig();
		$templatePaths = $this->_aimeos->getCustomPath( 'client/html' );

		$langid = $context->getLocale()->getLanguageId();
		$i18n = $this->_getI18n( array( $langid ) );

		// required for reloading to the current page
		$params = $this->request->getArguments();
		$params['target'] = $GLOBALS["TSFE"]->id;


		$view = new \MW_View_Default();

		$helper = new \MW_View_Helper_Url_Typo3( $view, $this->uriBuilder, $this->_getFixedParams( $config ) );
		$view->addHelper( 'url', $helper );

		$helper = new \MW_View_Helper_Translate_Default( $view, $i18n[$langid] );
		$view->addHelper( 'translate', $helper );

		$helper = new \MW_View_Helper_Partial_Default( $view, $config, $templatePaths );
		$view->addHelper( 'partial', $helper );

		$helper = new \MW_View_Helper_Parameter_Default( $view, $params );
		$view->addHelper( 'param', $helper );

		$helper = new \MW_View_Helper_Config_Default( $view, $config );
		$view->addHelper( 'config', $helper );

		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$helper = new \MW_View_Helper_Number_Default( $view, $sepDec, $sep1000 );
		$view->addHelper( 'number', $helper );

		$helper = new \MW_View_Helper_FormParam_Default( $view, array( $this->uriBuilder->getArgumentPrefix() ) );
		$view->addHelper( 'formparam', $helper );

		$helper = new \MW_View_Helper_Encoder_Default( $view );
		$view->addHelper( 'encoder', $helper );

		return $view;
	}


	/**
	 * Returns the cache object for the context
	 *
	 * @param \MShop_Context_Item_Interface $context Context object including config
	 * @param string $siteid Unique site ID
	 * @return \MW_Cache_Interface Cache object
	 */
	protected function _getCache( \MShop_Context_Item_Interface $context, $siteid )
	{
		$config = $context->getConfig();

		switch( Base::getExtConfig( 'cacheName', 'Typo3' ) )
		{
			case 'Typo3':
				\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
				$cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Cache\\CacheManager' )->getCache( 'aimeos' );

				$conf = array( 'siteid' => $config->get( 'mshop/cache/prefix' ) . $siteid );
				return \MW_Cache_Factory::createManager( 'Typo3', $conf, $cache );

			case 'None':
				$config->set( 'client/html/basket/cache/enable', false );
				return \MW_Cache_Factory::createManager( 'None', array(), null );

			default:
				return new MAdmin_Cache_Proxy_Default( $context );
		}
	}


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
	 * Returns the current context.
	 *
	 * @return MShop_Context_Item_Interface Context object
	 */
	protected function _getContext()
	{
		$config = $this->_getConfig();

		if( self::$_context === null )
		{
			$context = new \MShop_Context_Item_Default();

			$context->setConfig( $config );

			$dbm = new \MW_DB_Manager_PDO( $config );
			$context->setDatabaseManager( $dbm );

			if( isset( $GLOBALS['TSFE']->fe_user ) ) {
				$session = new \MW_Session_Typo3( $GLOBALS['TSFE']->fe_user );
			} else {
				$session = new \MW_Session_None();
			}
			$context->setSession( $session );

			$logger = \MAdmin_Log_Manager_Factory::createManager( $context );
			$context->setLogger( $logger );

			if( TYPO3_MODE === 'FE' && $GLOBALS['TSFE']->loginUser == 1 )
			{
				$context->setEditor( $GLOBALS['TSFE']->fe_user->user['username'] );
				$context->setUserId( $GLOBALS['TSFE']->fe_user->user[$GLOBALS['TSFE']->fe_user->userid_column] );
			}

			self::$_context = $context;
		}

		self::$_context->setConfig( $config );

		return self::$_context;
	}


	/**
	 * Creates new translation objects.
	 *
	 * @param array $langIds List of two letter ISO language IDs
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	protected function _getI18n( array $languageIds )
	{
		$i18nPaths = Base::getAimeos()->getI18nPaths();

		foreach( $languageIds as $langid )
		{
			if( !isset( self::$_i18n[$langid] ) )
			{
				$i18n = new \MW_Translation_Zend2( $i18nPaths, 'gettext', $langid, array( 'disableNotices' => true ) );

				if( function_exists( 'apc_store' ) === true && Base::getExtConfig( 'useAPC', false ) == true ) {
					$i18n = new \MW_Translation_Decorator_APC( $i18n, Base::getExtConfig( 'apcPrefix', 't3:' ) );
				}

				if( isset( $this->settings['i18n'][$langid] ) )
				{
					$translations = Base::parseTranslations( (array) $this->settings['i18n'][$langid] );
					$i18n = new \MW_Translation_Decorator_Memory( $i18n, $translations );
				}

				self::$_i18n[$langid] = $i18n;
			}
		}

		return self::$_i18n;
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
	 * Returns the fixed parameters that should be included in every URL
	 *
	 * @param \MW_Config_Interface $config Config object
	 * @return array Associative list of site, language and currency if available
	 */
	protected function _getFixedParams( \MW_Config_Interface $config )
	{
		$fixed = array();

		$name = $config->get( 'typo3/param/name/site', 'loc-site' );
		if( $this->request->hasArgument( $name ) === true ) {
			$fixed[$name] = $this->request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/language', 'loc-language' );
		if( $this->request->hasArgument( $name ) === true ) {
			$fixed[$name] = $this->request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/currency', 'loc-currency' );
		if( $this->request->hasArgument( $name ) === true ) {
			$fixed[$name] = $this->request->getArgument( $name );
		}

		return $fixed;
	}


	/**
	 * Returns the output of the client and adds the header.
	 *
	 * @param Client_Html_Interface $client Html client object
	 * @return string HTML code for inserting into the HTML body
	 */
	protected function _getClientOutput( \Client_Html_Interface $client )
	{
		$client->setView( $this->_createView() );
		$client->process();

		$this->response->addAdditionalHeaderData( (string) $client->getHeader() );

		return $client->getBody();
	}
}
