<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


/**
 * Aimeos catalog controller.
 *
 * @package TYPO3_Aimeos
 */
class Tx_Aimeos_Controller_CatalogController extends Tx_Aimeos_Controller_Abstract
{
	/**
	 * Renders the catalog filter section.
	 */
	public function filterAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Filter_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the catalog counts.
	 */
	public function countAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Count_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the catalog stage section.
	 */
	public function stageAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Stage_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the catalog stock section.
	 */
	public function stockAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Stock_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the catalog list section.
	 */
	public function listAction()
	{
		if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
			$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
		}

		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_List_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders a list of product names in JSON format.
	 */
	public function listsimpleAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_List_Factory::createClient( $this->_getContext(), $templatePaths, 'Simple' );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the catalog detail section.
	 */
	public function detailAction()
	{
		if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
			$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
		}

		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Detail_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}


	/**
	 * Renders the user session related catalog section.
	 */
	public function sessionAction()
	{
		$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = Client_Html_Catalog_Session_Factory::createClient( $this->_getContext(), $templatePaths );

		return $this->_getClientOutput( $client );
	}
}
