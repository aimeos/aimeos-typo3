<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016-2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Aimeos config class
 *
 * @package TYPO3
 */
class Config
{
	private static $config;


	/**
	 * Creates a new configuration object.
	 *
     * @param array $paths Paths to the configuration directories
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return \Aimeos\MW\Config\Iface Configuration object
	 */
	public static function get( array $paths, array $local = array() )
	{
		if( self::$config === null )
		{
			// Using extension config directories
			if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] ) )
			{
				ksort( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] );
				foreach( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs'] as $dir )
				{
					if( ( $absPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName( $dir ) ) !== '' ) {
						$paths[] = $absPath;
					}
				}
			}

			$conf = new \Aimeos\MW\Config\PHPArray( array(), $paths );

			if( (bool) \Aimeos\Aimeos\Base::getExtConfig( 'useAPC', false ) === true ) {
				$conf = new \Aimeos\MW\Config\Decorator\APC( $conf, \Aimeos\Aimeos\Base::getExtConfig( 'apcPrefix', 't3:' ) );
			}

			self::$config = $conf;
		}

		if( isset( $local['typo3']['tsconfig'] ) ) {
			$local = array_replace_recursive( $local, \Aimeos\Aimeos\Base::parseTS( $local['typo3']['tsconfig'] ) );
		}

		return new \Aimeos\MW\Config\Decorator\Memory( self::$config, $local );
	}
}
