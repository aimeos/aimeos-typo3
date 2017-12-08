<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Aimeos basket controller.
 *
 * @package TYPO3
 */
class BasketController extends AbstractController
{
	/**
	 * Processes requests and renders the basket.
	 */
	public function indexAction()
	{
		$client = \Aimeos\Client\Html\Basket\Standard\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders a small basket.
	 */
	public function smallAction()
	{
		$client = \Aimeos\Client\Html\Basket\Mini\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the related basket.
	 */
	public function relatedAction()
	{
		$client = \Aimeos\Client\Html\Basket\Related\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}
}
