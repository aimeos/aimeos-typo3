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
	private $aimeos;


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @param array $extDirs List of directories with Aimeos extensions
	 * @return \Aimeos\Bootstrap Aimeos bootstrap object
	 */
	public function get( $extDirs = array() )
	{
		if( $this->aimeos === null )
		{
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

			$this->aimeos = new \Aimeos\Bootstrap( $extDirs, false );
		}

		return $this->aimeos;
	}
}
