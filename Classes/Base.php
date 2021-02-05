<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos;

use \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use \TYPO3\CMS\Core\Utility\GeneralUtility;


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );

if( file_exists( $aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}


/**
 * Aimeos base class with common functionality.
 *
 * @package TYPO3
 */
class Base
{
	private static $extConfig;


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap Aimeos bootstrap object
	 */
	public static function getAimeos() : \Aimeos\Bootstrap
	{
		$name = 'Aimeos\Aimeos\Base\Aimeos';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'] ) instanceof \Closure ) {
				return $name();
			}
		}

		return $name::get();
	}


	/**
	 * Creates a new configuration object
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return \Aimeos\MW\Config\Iface Configuration object
	 */
	public static function getConfig( array $local = [] ) : \Aimeos\MW\Config\Iface
	{
		$name = 'Aimeos\Aimeos\Base\Config';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'] ) instanceof \Closure ) {
				return $name( self::getAimeos()->getConfigPaths(), $local );
			}
		}

		return $name::get( self::getAimeos()->getConfigPaths(), $local );
	}


	/**
	 * Returns the current context
	 *
	 * @param \Aimeos\MW\Config\Iface Configuration object
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function getContext( \Aimeos\MW\Config\Iface $config ) : \Aimeos\MShop\Context\Item\Iface
	{
		$name = 'Aimeos\Aimeos\Base\Context';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'] ) instanceof \Closure ) {
				return $name( $config );
			}
		}

		return $name::get( $config );
	}


	/**
	 * Returns the extension configuration
	 *
	 * @param string Name of the configuration setting
	 * @param mixed Value returned if no value in extension configuration was found
	 * @return mixed Value associated with the configuration setting
	 */
	public static function getExtConfig( string $name, $default = null )
	{
		if( self::$extConfig === null ) {
			self::$extConfig = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\Configuration\ExtensionConfiguration' )->get( 'aimeos' );
		}

		if( isset( self::$extConfig[$name] ) ) {
			return self::$extConfig[$name];
		}

		return $default;
	}


	/**
	 * Creates new translation objects
	 *
	 * @param array $languageIds List of two letter ISO language IDs
	 * @param array $local List of local translation entries overwriting the standard ones
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	public static function getI18n( array $languageIds, array $local = [] ) : array
	{
		$name = 'Aimeos\Aimeos\Base\I18n';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'] ) instanceof \Closure ) {
				return $name( self::getAimeos()->getI18nPaths(), $languageIds, $local );
			}
		}

		return $name::get( self::getAimeos()->getI18nPaths(), $languageIds, $local );
	}


	/**
	 * Creates a new locale object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function getLocale( \Aimeos\MShop\Context\Item\Iface $context,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null ) : \Aimeos\MShop\Locale\Item\Iface
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale_frontend'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale_frontend'] ) instanceof \Closure ) {
				return $name( $context, $request );
			}
		}

		$name = 'Aimeos\Aimeos\Base\Locale';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'] ) ) {
			$name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'];
		}

		return $name::get( $context, $request );
	}


	/**
	 * Creates a new locale object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function getLocaleBackend( \Aimeos\MShop\Context\Item\Iface $context,
		string $sitecode ) : \Aimeos\MShop\Locale\Item\Iface
	{
		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale_backend'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale_backend'] ) instanceof \Closure ) {
				return $name( $context, $sitecode );
			}
		}

		$name = 'Aimeos\Aimeos\Base\Locale';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'] ) ) {
			$name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'];
		}

		return $name::getBackend( $context, $sitecode );
	}


	/**
	 * Returns the version of the Aimeos TYPO3 extension
	 *
	 * @return string Version string
	 */
	public static function getVersion() : string
	{
		$match = [];
		$content = @file_get_contents( dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'ext_emconf.php' );

		if( preg_match( "/'version' => '([^']+)'/", $content, $match ) === 1 ) {
			return $match[1];
		}

		return '';
	}


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder|\TYPO3\CMS\Core\Routing\RouterInterface $uriBuilder URL builder
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string|null $langid ISO code of the current language ("de"/"de_CH") or null for no translation
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public static function getView( \Aimeos\MShop\Context\Item\Iface $context, $uriBuilder, array $templatePaths,
		\TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, string $langid = null ) : \Aimeos\MW\View\Iface
	{
		$name = 'Aimeos\Aimeos\Base\View';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'] ) ) {
			if( ( $name = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'] ) instanceof \Closure ) {
				return $name( $context, $uriBuilder, $templatePaths, $request, $langid );
			}
		}

		return $name::get( $context, $uriBuilder, $templatePaths, $request, $langid );
	}


	/**
	 * Parses TypoScript configuration string.
	 *
	 * @param string $tsString TypoScript string
	 * @return array Mulit-dimensional, associative list of key/value pairs
	 * @throws Exception If parsing the configuration string fails
	 */
	public static function parseTS( string $tsString ) : array
	{
		$parser = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser' );
		$parser->parse( TypoScriptParser::checkIncludeLines( $tsString ) );

		if( !empty( $parser->errors ) ) {
			throw new \InvalidArgumentException( 'Invalid TypoScript: \"' . $tsString . "\"\n" . print_r( $parser->errors, true ) );
		}

		$service = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\TypoScript\TypoScriptService' );
		$tsConfig = $service->convertTypoScriptArrayToPlainArray( $parser->setup );

		// Allows "plugin.tx_aimeos.settings." prefix everywhere
		if( isset( $tsConfig['plugin']['tx_aimeos']['settings'] )
			&& is_array( $tsConfig['plugin']['tx_aimeos']['settings'] )
		) {
			$tsConfig = array_replace_recursive( $tsConfig['plugin']['tx_aimeos']['settings'], $tsConfig );
			unset( $tsConfig['plugin']['tx_aimeos'] );
		}

		return $tsConfig;
	}


	/**
	 * Clears basket information on logout
	 *
	 * @return void
	 */
	public static function logout()
	{
		$session = self::getContext( self::getConfig() )->getSession();

		$session->remove( array_keys( $session->get( 'aimeos/basket/list', [] ) ) );
		$session->remove( array_keys( $session->get( 'aimeos/basket/cache', [] ) ) );
	}


	/**
	 * Doing a complete ACPu wipe with the red system cache.
	 *
	 * @hook clearCachePostProc
	 *
	 * @param array $cacheType The caching type.
	 *
	 * @return void
	 */
	public static function clearCache( array $cacheType )
	{
		if( isset( $cacheType['cacheCmd'] ) && $cacheType['cacheCmd'] === 'all'
			&& (bool) static::getExtConfig( 'useAPC', false ) === true
			&& function_exists( 'apcu_clear_cache' )
		) {
			apcu_clear_cache();
		}
	}
}
