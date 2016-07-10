<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
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
    private $aimeos;
	private static $config;


    /**
     * Initializes the object
     *
     * @param \Aimeos\Bootstrap $aimeos Aimeos bootstrap object
     */
    public function __construct( \Aimeos\Bootstrap $aimeos )
    {
        $this->aimeos = $aimeos;
    }


	/**
	 * Creates a new configuration object.
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return MW_Config_Interface Configuration object
	 */
	public function get( array $local = array() )
	{
		if( self::$config === null )
		{
			$configPaths = $this->aimeos->getConfigPaths();

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

			$conf = new \Aimeos\MW\Config\PHPArray( array(), $configPaths );

			if( function_exists( 'apc_store' ) === true && (bool) \Aimeos\Aimeos\Base::getExtConfig( 'useAPC', false ) === true ) {
				$conf = new \Aimeos\MW\Config\Decorator\APC( $conf, \Aimeos\Aimeos\Base::getExtConfig( 'apcPrefix', 't3:' ) );
			}

			self::$config = $conf;
		}

		return new \Aimeos\MW\Config\Decorator\Memory( self::$config, $local );
	}
}
