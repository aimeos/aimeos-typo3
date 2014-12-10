<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Flexform;


/**
 * Aimeos abstract flexform helper.
 *
 * @package TYPO3_Aimeos
 */
abstract class AbstractHelper
{
	private $_context;


	/**
	 * Returns the current context.
	 *
	 * @return MShop_Context_Item_Interface Context object
	 */
	protected function _getContext()
	{
		if( $this->_context === null )
		{
			$ds = DIRECTORY_SEPARATOR;

			// Important! Sets include paths
			$aimeos = \Aimeos\Aimeos\Base::getAimeos();
			$context = new \MShop_Context_Item_Default();


			$configPaths = $aimeos->getConfigPaths( 'mysql' );
			$configPaths[] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' ) . 'Resources' . $ds . 'Private' . $ds . 'Config';

			$conf = new \MW_Config_Array( array(), $configPaths );
			$conf = new \MW_Config_Decorator_Memory( $conf );
			$context->setConfig( $conf );

			$dbm = new \MW_DB_Manager_PDO( $conf );
			$context->setDatabaseManager( $dbm );

			$logger = \MAdmin_Log_Manager_Factory::createManager( $context );
			$context->setLogger( $logger );

			$context->setEditor( 'flexform' );


			$this->_context = $context;
		}

		return $this->_context;
	}
}
