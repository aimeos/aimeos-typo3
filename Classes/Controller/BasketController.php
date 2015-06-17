<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Aimeos basket controller.
 *
 * @package TYPO3_Aimeos
 */
class BasketController extends AbstractController
{
	/**
	 * Processes requests and renders the basket.
	 */
	public function indexAction()
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Client_Html_Basket_Standard_Factory::createClient( $this->getContext(), $templatePaths );

		return $this->getClientOutput( $client );
	}


	/**
	 * Renders a small basket.
	 */
	public function smallAction()
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Client_Html_Basket_Mini_Factory::createClient( $this->getContext(), $templatePaths );

		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the related basket.
	 */
	public function relatedAction()
	{
		$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$client = \Client_Html_Basket_Related_Factory::createClient( $this->getContext(), $templatePaths );

		return $this->getClientOutput( $client );
	}
}
