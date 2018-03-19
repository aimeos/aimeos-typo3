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
 * Aimeos catalog controller.
 *
 * @package TYPO3
 */
class CatalogController extends AbstractController
{
	/**
	 * Renders the catalog attribute section.
	 */
	public function attributeAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Attribute\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog detail section.
	 */
	public function detailAction()
	{
		if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
			$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
		}

		$client = \Aimeos\Client\Html\Catalog\Detail\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog filter section.
	 */
	public function filterAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Filter\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog counts.
	 */
	public function countAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Count\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog list section.
	 */
	public function listAction()
	{
		if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) ) {
			$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
		}

		$client = \Aimeos\Client\Html\Catalog\Lists\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog search section.
	 */
	public function searchAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Search\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the user session related catalog section.
	 */
	public function sessionAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Session\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog stage section.
	 */
	public function stageAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Stage\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog stock section.
	 */
	public function stockAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Stock\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders a list of product names in JSON format.
	 */
	public function suggestAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Suggest\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog tree section.
	 */
	public function treeAction()
	{
		$client = \Aimeos\Client\Html\Catalog\Tree\Factory::createClient( $this->getContext() );
		return $this->getClientOutput( $client );
	}
}
