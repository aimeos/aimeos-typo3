<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Base;


/**
 * Aimeos bootstrap class
 *
 * @package TYPO3
 */
class Aimeos
{
	private static $aimeos;


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @return \Aimeos\Bootstrap Aimeos bootstrap object
	 */
	public static function get()
	{
		if( self::$aimeos === null )
		{
			$extDirs = array();

			// Extension directories
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

			self::$aimeos = new \Aimeos\Bootstrap( $extDirs, false );
		}

		return self::$aimeos;
	}

	/**
	 * Clears user information on logout
	 *
	 * @return void
	 */
	public static function logout()
	{
		$localConfig = array(
			'clearArticles' => \Aimeos\Aimeos\Base::getExtConfig( 'clearArticles', 0 ) == true,
			'clearCoupons' => \Aimeos\Aimeos\Base::getExtConfig( 'clearCoupons', 0 ) == true,
			'clearAddresses' => \Aimeos\Aimeos\Base::getExtConfig( 'clearAddresses', 0 ) == true,
			'clearPayment' => \Aimeos\Aimeos\Base::getExtConfig( 'clearPayment', 0 ) == true,
			'clearDelivery' => \Aimeos\Aimeos\Base::getExtConfig( 'clearDelivery', 0 ) == true,
		);

		$config = \Aimeos\Aimeos\Base::getConfig( $localConfig );
		$context = \Aimeos\Aimeos\Base::getContext( $config );	
		$controller = \Aimeos\Controller\Frontend\Factory::createController($context, 'basket');
		$controller->clear( true );
	}
}
