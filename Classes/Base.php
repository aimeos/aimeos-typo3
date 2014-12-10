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
	static private $_aimeos;
	static private $_config;
	static private $_context;
	static private $_extConfig;


	/**
	 * Creates a new configuration object.
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return MW_Config_Interface Configuration object
	 */
	public static function getConfig( array $local = array() )
	{
		if( self::$_config === null )
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

			self::$_config = $conf;
		}

		return new \MW_Config_Decorator_Memory( self::$_config, $local );
	}


	/**
	 * Returns the Aimeos object.
	 *
	 * @return Aimeos Aimeos object
	 */
	public static function getAimeos()
	{
		if( self::$_aimeos === null )
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

			self::$_aimeos = new \Arcavias( $extDirs, false, $libPath );
		}

		return self::$_aimeos;
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
		if( self::$_extConfig === null )
		{
			if( ( $conf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['aimeos'] ) ) === false ) {
				$conf = array();
			}

			self::$_extConfig = $conf;
		}

		if( isset( self::$_extConfig[$name] ) ) {
			return self::$_extConfig[$name];
		}

		return $default;
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
			throw new Exception( $msg );
		}

		$tsConfig = self::_convertTypoScriptArrayToPlainArray( $parser->setup );

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
	protected static function _convertTypoScriptArrayToPlainArray(array $typoScriptArray)
	{
		foreach ($typoScriptArray as $key => &$value) {
			if (substr($key, -1) === '.') {
				$keyWithoutDot = substr($key, 0, -1);
				$hasNodeWithoutDot = array_key_exists($keyWithoutDot, $typoScriptArray);
				$typoScriptNodeValue = $hasNodeWithoutDot ? $typoScriptArray[$keyWithoutDot] : NULL;
				if (is_array($value)) {
					$typoScriptArray[$keyWithoutDot] = self::_convertTypoScriptArrayToPlainArray($value);
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
}
