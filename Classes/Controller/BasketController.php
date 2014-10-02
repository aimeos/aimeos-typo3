<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


/**
 * Aimeos basket controller.
 *
 * @package TYPO3_Aimeos
 */
class Tx_Aimeos_Controller_BasketController extends Tx_Aimeos_Controller_Abstract
{
	/**
	 * Processes requests and renders the basket.
	 */
	public function indexAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Basket_Standard_Factory::createClient( $this->_getContext(), $templatePaths );

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
	 * Renders a small basket.
	 */
	public function smallAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Basket_Mini_Factory::createClient( $this->_getContext(), $templatePaths );

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
	 * Renders the related basket.
	 */
	public function relatedAction()
	{
		try
		{
			$templatePaths = Tx_Aimeos_Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = Client_Html_Basket_Related_Factory::createClient( $this->_getContext(), $templatePaths );

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
