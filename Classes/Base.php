<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos;


require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR . 'autoload.php';


/**
 * Aimeos base class with common functionality.
 *
 * @package TYPO3_Aimeos
 */
class Base
{
	private static $aimeos;
	private static $config;
	private static $context;
	private static $extConfig;
	private static $i18n = array();


	/**
	 * Returns the Aimeos object.
	 *
	 * @return Aimeos Aimeos object
	 */
	public static function getAimeos()
	{
		if( self::$aimeos === null )
		{
			$ds = DIRECTORY_SEPARATOR;
			$libPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );
			$libPath .= 'Resources' . $ds . 'Libraries' . $ds . 'arcavias' . $ds . 'arcavias-core';

			// Hook for processing extension directories
			$extDirs = array();
			if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['extDirs'] ) )
			{
				ksort( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['extDirs'] );

				foreach( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['extDirs'] as $dir )
				{
					$absPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName( $dir );
					if( !empty( $absPath ) ) {
						$extDirs[] = $absPath;
					}
				}
			}

			self::$aimeos = new \Arcavias( $extDirs, false, $libPath );
		}

		return self::$aimeos;
	}


	/**
	 * Returns the cache object for the context
	 *
	 * @param \MShop_Context_Item_Interface $context Context object including config
	 * @param string $siteid Unique site ID
	 * @return \MW_Cache_Interface Cache object
	 */
	protected static function getCache( \MShop_Context_Item_Interface $context )
	{
		$config = $context->getConfig();

		switch( Base::getExtConfig( 'cacheName', 'Typo3' ) )
		{
			case 'None':
				$config->set( 'client/html/basket/cache/enable', false );
				return \MW_Cache_Factory::createManager( 'None', array(), null );

			case 'Typo3':
				\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
				$manager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Cache\\CacheManager' );

				return new \MAdmin_Cache_Proxy_Typo3( $context, $manager->getCache( 'aimeos' ) );

			default:
				return new \MAdmin_Cache_Proxy_Default( $context );
		}
	}


	/**
	 * Creates a new configuration object.
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return MW_Config_Interface Configuration object
	 */
	public static function getConfig( array $local = array() )
	{
		if( self::$config === null )
		{
			$configPaths = self::getAimeos()->getConfigPaths( 'mysql' );

			// Hook for processing extension config directories
			if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] ) )
			{
				ksort( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] );

				foreach( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] as $dir )
				{
					$absPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName( $dir );
					if( !empty( $absPath ) ) {
						$configPaths[] = $absPath;
					}
				}
			}

			$conf = new \MW_Config_Array( array(), $configPaths );

			if( function_exists( 'apc_store' ) === true && self::getExtConfig( 'useAPC', false ) == true ) {
				$conf = new \MW_Config_Decorator_APC( $conf, self::getExtConfig( 'apcPrefix', 't3:' ) );
			}

			self::$config = $conf;
		}

		return new \MW_Config_Decorator_Memory( self::$config, $local );
	}


	/**
	 * Returns the current context.
	 *
	 * @param \MW_Config_Interface Configuration object
	 * @return MShop_Context_Item_Interface Context object
	 */
	public static function getContext( \MW_Config_Interface $config )
	{
		if( self::$context === null )
		{
			$context = new \MShop_Context_Item_Default();
			$context->setConfig( $config );

			$dbm = new \MW_DB_Manager_PDO( $config );
			$context->setDatabaseManager( $dbm );

			$logger = \MAdmin_Log_Manager_Factory::createManager( $context );
			$context->setLogger( $logger );

			$cache = self::getCache( $context );
			$context->setCache( $cache );

			$mailer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Mail\MailMessage' );
			$context->setMail( new \MW_Mail_Typo3( $mailer ) );

			if( isset( $GLOBALS['TSFE']->fe_user ) ) {
				$session = new \MW_Session_Typo3( $GLOBALS['TSFE']->fe_user );
			} else {
				$session = new \MW_Session_None();
			}
			$context->setSession( $session );

			self::$context = $context;
		}

		self::$context->setConfig( $config );

		return self::$context;
	}


	/**
	 * Returns the extension configuration.
	 *
	 * @param string Name of the configuration setting
	 * @param mixed Value returned if no value in extension configuration was found
	 * @return mixed Value associated with the configuration setting
	 */
	public static function getExtConfig( $name, $default = null )
	{
		if( self::$extConfig === null )
		{
			if( ( $conf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['aimeos'] ) ) === false ) {
				$conf = array();
			}

			self::$extConfig = $conf;
		}

		if( isset( self::$extConfig[$name] ) ) {
			return self::$extConfig[$name];
		}

		return $default;
	}


	/**
	 * Creates new translation objects.
	 *
	 * @param array $langIds List of two letter ISO language IDs
	 * @param array $local List of local translation entries overwriting the standard ones
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	public static function getI18n( array $languageIds, array $local = array() )
	{
		$i18nList = array();
		$i18nPaths = self::getAimeos()->getI18nPaths();

		foreach( $languageIds as $langid )
		{
			if( !isset( self::$i18n[$langid] ) )
			{
				$i18n = new \MW_Translation_Zend2( $i18nPaths, 'gettext', $langid, array( 'disableNotices' => true ) );

				if( function_exists( 'apc_store' ) === true && self::getExtConfig( 'useAPC', false ) == true ) {
					$i18n = new \MW_Translation_Decorator_APC( $i18n, self::getExtConfig( 'apcPrefix', 't3:' ) );
				}

				self::$i18n[$langid] = $i18n;
			}

			$i18nList[$langid] = self::$i18n[$langid];

			if( isset( $local[$langid] ) )
			{
				$translations = self::parseTranslations( (array) $local[$langid] );
				$i18nList[$langid] = new \MW_Translation_Decorator_Memory( $i18nList[$langid], $translations );
			}
		}

		return $i18nList;
	}


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param \MW_Config_Interface $config Configuration object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URL builder object
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string|null $locale Code of the current language or null for no translation
	 * @return MW_View_Interface View object
	 */
	public static function getView( \MW_Config_Interface $config, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder,
		array $templatePaths, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $locale = null )
	{
		$params = $fixed = array();

		if( $request !== null && $locale !== null )
		{
			$fixed = self::getFixedParams( $config, $request );

			// required for reloading to the current page
			$params = $request->getArguments();
			$params['target'] = $GLOBALS["TSFE"]->id;

			$i18n = Base::getI18n( array( $locale ), $config->get( 'i18n', array() ) );
			$translation = $i18n[$locale];
		}
		else
		{
			$translation = new \MW_Translation_None( 'en' );
		}


		$view = new \MW_View_Default();

		// workaround for TYPO3 6.2 bug (UriBuilder is incomplete)
		if( \TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version() >= '7.0.0' ) {
			$helper = new \MW_View_Helper_Url_Typo3( $view, $uriBuilder, $fixed );
		} else {
			$helper = new \MW_View_Helper_Url_None( $view );
		}
		$view->addHelper( 'url', $helper );

		$helper = new \MW_View_Helper_Translate_Default( $view, $translation );
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

		$helper = new \MW_View_Helper_FormParam_Default( $view, array( $uriBuilder->getArgumentPrefix() ) );
		$view->addHelper( 'formparam', $helper );

		$helper = new \MW_View_Helper_Encoder_Default( $view );
		$view->addHelper( 'encoder', $helper );

		return $view;
	}


	/**
	 * Parses TypoScript configuration string.
	 *
	 * @param array $entries User-defined translation entries via TypoScript
	 * @return array Associative list of translation domain and original string / list of tranlations
	 */
	public static function parseTranslations( array $entries )
	{
		$translations = array();

		foreach( $entries as $entry )
		{
			if( isset( $entry['domain'] ) && isset( $entry['string'] ) && isset( $entry['trans'] ) )
			{
				$string = str_replace( '\\n', "\n", $entry['string'] );
				$trans = array();

				foreach( (array) $entry['trans'] as $tx ) {
					$trans[] = str_replace( '\\n', "\n", $tx );
				}

				$translations[$entry['domain']][$string] = $trans;
			}
		}

		return $translations;
	}


	/**
	 * Parses TypoScript configuration string.
	 *
	 * @param string $tsString TypoScript string
	 * @return array Mulit-dimensional, associative list of key/value pairs
	 * @throws Exception If parsing the configuration string fails
	 */
	public static function parseTS( $tsString )
	{
		$parser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\TypoScript\\Parser\\TypoScriptParser' );
		$parser->parse( $tsString );

		if( !empty( $parser->errors ) )
		{
			$msg = $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:default.error.tsconfig.invalid' );
			throw new \Exception( $msg );
		}

		$tsConfig = self::convertTypoScriptArrayToPlainArray( $parser->setup );

		// Allows "plugin.tx_aimeos.settings." prefix everywhere
		if( isset( $tsConfig['plugin']['tx_aimeos']['settings'] )
			&& is_array( $tsConfig['plugin']['tx_aimeos']['settings'] )
		) {
			return $tsConfig['plugin']['tx_aimeos']['settings'];
		}

		return $tsConfig;
	}


	/**
	 * Removes dots from config keys (copied from Extbase TypoScriptService class available since TYPO3 6.0)
	 *
	 * @param array $typoScriptArray TypoScript configuration array
	 * @return array Multi-dimensional, associative list of key/value pairs without dots in keys
	 */
	protected static function convertTypoScriptArrayToPlainArray(array $typoScriptArray)
	{
		foreach ($typoScriptArray as $key => &$value) {
			if (substr($key, -1) === '.') {
				$keyWithoutDot = substr($key, 0, -1);
				$hasNodeWithoutDot = array_key_exists($keyWithoutDot, $typoScriptArray);
				$typoScriptNodeValue = $hasNodeWithoutDot ? $typoScriptArray[$keyWithoutDot] : NULL;
				if (is_array($value)) {
					$typoScriptArray[$keyWithoutDot] = self::convertTypoScriptArrayToPlainArray($value);
					if (!is_null($typoScriptNodeValue)) {
						$typoScriptArray[$keyWithoutDot]['_typoScriptNodeValue'] = $typoScriptNodeValue;
					}
					unset($typoScriptArray[$key]);
				} else {
					$typoScriptArray[$keyWithoutDot] = NULL;
				}
			}
		}
		return $typoScriptArray;
	}


	/**
	 * Returns the fixed parameters that should be included in every URL
	 *
	 * @param \MW_Config_Interface $config Config object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request Request object
	 * @return array Associative list of site, language and currency if available
	 */
	protected static function getFixedParams( \MW_Config_Interface $config,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request )
	{
		$fixed = array();

		$name = $config->get( 'typo3/param/name/site', 'loc-site' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/language', 'loc-language' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		$name = $config->get( 'typo3/param/name/currency', 'loc-currency' );
		if( $request->hasArgument( $name ) === true ) {
			$fixed[$name] = $request->getArgument( $name );
		}

		return $fixed;
	}
}
