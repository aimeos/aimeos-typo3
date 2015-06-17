<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Aimeos checkout controller.
 *
 * @package TYPO3_Aimeos
 */
class CheckoutController extends AbstractController
{
	/**
	 * Processes requests and renders the checkout process.
	 */
	public function indexAction()
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Client_Html_Checkout_Standard_Factory::createClient( $this->getContext(), $templatePaths );

		return $this->getClientOutput( $client );
	}


	/**
	 * Processes requests and renders the checkout confirmation.
	 */
	public function confirmAction()
	{
		$context = $this->getContext();
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Client_Html_Checkout_Confirm_Factory::createClient( $context, $templatePaths );

		$view = $context->getView();
		$helper = new \MW_View_Helper_Parameter_Default( $view, $_REQUEST );
		$view->addHelper( 'param', $helper );

		$client->setView( $view );
		$client->process();

		$this->response->addAdditionalHeaderData( $client->getHeader() );

		return $client->getBody();
	}


	/**
	 * Processes update requests from payment service providers.
	 */
	public function updateAction()
	{
		try
		{
			$context = $this->getContext();
			$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
			$client = \Client_Html_Checkout_Update_Factory::createClient( $context, $templatePaths );

			$view = $context->getView();
			$helper = new \MW_View_Helper_Parameter_Default( $view, $_REQUEST );
			$view->addHelper( 'param', $helper );

			$client->setView( $view );
			$client->process();

			$this->response->addAdditionalHeaderData( $client->getHeader() );

			return $client->getBody();
		}
		catch( Exception $e )
		{
			@header( 'HTTP/1.1 500 Internal server error', true, 500 );
			return 'Error: ' . $e->getMessage();
		}
	}
}
