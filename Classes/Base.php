<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


$localautoloader = dirname( __DIR__ ) . '/Resources/Libraries/autoload.php';

if( file_exists( $localautoloader ) === true ) {
	require_once $localautoloader;
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
	public static function getAimeos()
	{
		$className = 'Aimeos\Aimeos\Base\Aimeos';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'];
		}

		return $className::get();
	}


	/**
	 * Creates a new configuration object
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return MW_Config_Interface Configuration object
	 */
	public static function getConfig( array $local = array() )
	{
		$className = 'Aimeos\Aimeos\Base\Config';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'];
		}

		return $className::get( self::getAimeos()->getConfigPaths(), $local );
	}


	/**
	 * Returns the current context
	 *
	 * @param \Aimeos\MW\Config\Iface Configuration object
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function getContext( \Aimeos\MW\Config\Iface $config )
	{
		$className = 'Aimeos\Aimeos\Base\Context';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'];
		}

		return $className::get( $config );
	}


	/**
	 * Returns the extension configuration
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
	 * Creates new translation objects
	 *
	 * @param array $languageIds List of two letter ISO language IDs
	 * @param array $local List of local translation entries overwriting the standard ones
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	public static function getI18n( array $languageIds, array $local = array() )
	{
		$className = 'Aimeos\Aimeos\Base\I18n';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'];
		}

		return $className::get( self::getAimeos()->getI18nPaths(), $languageIds, $local );
	}


	/**
	 * Creates a new locale object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function getLocale( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		$className = 'Aimeos\Aimeos\Base\Locale';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'];
		}

		return $className::get( $context, $request );
	}


	/**
	 * Creates a new locale object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function getLocaleBackend( \Aimeos\MShop\Context\Item\Iface $context, $sitecode )
	{
		$className = 'Aimeos\Aimeos\Base\Locale';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'];
		}

		return $className::getBackend( $context, $sitecode );
	}


	/**
	 * Returns the version of the Aimeos TYPO3 extension
	 *
	 * @return string Version string
	 */
	public static function getVersion()
	{
		$match = array();
		$content = @file_get_contents( dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'ext_emconf.php' );

		if( preg_match( "/'version' => '([^']+)'/", $content, $match ) === 1 ) {
			return $match[1];
		}

		return '';
	}


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param \Aimeos\MW\Config\Iface $context Config object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URL builder object
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string|null $langid ISO code of the current language ("de"/"de_CH") or null for no translation
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public static function getView( \Aimeos\MW\Config\Iface $config, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder,
		array $templatePaths, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $langid = null )
	{
		$className = 'Aimeos\Aimeos\Base\View';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'];
		}

		return $className::get( $config, $uriBuilder, $templatePaths, $request, $langid );
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
		$parser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser' );
		$parser->parse( $tsString );

		if( !empty( $parser->errors ) ) {
			throw new \InvalidArgumentException( 'Invalid TypoScript: \"' . $tsString . "\"\n" . print_r( $parser->errors, true ) );
		}

		$service = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Extbase\Service\TypoScriptService' );
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
}
