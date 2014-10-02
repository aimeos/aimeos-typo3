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
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Filter_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the catalog counts.
	 */
	public function countAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Count_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the catalog stage section.
	 */
	public function stageAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Stage_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the catalog stock section.
	 */
	public function stockAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Stock_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the catalog list section.
	 */
	public function listAction()
	{
		try
		{
			if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
				$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
			}

			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_List_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders a list of product names in JSON format.
	 */
	public function listsimpleAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_List_Factory::createClient( $this->_getContext(), $templatePaths, 'Simple' );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the catalog detail section.
	 */
	public function detailAction()
	{
		try
		{
			if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
				$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
			}

			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Detail_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}


	/**
	 * Renders the user session related catalog section.
	 */
	public function sessionAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Catalog_Session_Factory::createClient( $this->_getContext(), $templatePaths );

			return $this->_getClientOutput( $client );
		}
		catch( Exception $e )
		{
			t3lib_FlashMessageQueue::addMessage( new t3lib_FlashMessage(
				'An error occured. Please go back to the previous page and try again',
				'Error',
				t3lib_Flashmessage::ERROR
			) );
		}
	}
}
