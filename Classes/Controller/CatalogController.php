<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use TYPO3\CMS\Core\Utility\GeneralUtility;


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
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/attribute' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog counts.
	 */
	public function countAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/count' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog detail section.
	 */
	public function detailAction()
	{
		$this->removeMetatags();
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/detail' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog filter section.
	 */
	public function filterAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/filter' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog home.
	 */
	public function homeAction()
	{
		$this->removeMetatags();
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/home' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog list section.
	 */
	public function listAction()
	{
		$this->removeMetatags();
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/lists' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog price section.
	 */
	public function priceAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/price' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog search section.
	 */
	public function searchAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/search' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the user session related catalog section.
	 */
	public function sessionAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/session' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog stage section.
	 */
	public function stageAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/stage' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog stock section.
	 */
	public function stockAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/stock' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders a list of product names in JSON format.
	 */
	public function suggestAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/suggest' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog supplier section.
	 */
	public function supplierAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/supplier' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the catalog tree section.
	 */
	public function treeAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'catalog/tree' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Removes the meta tags if available
	 */
	protected function removeMetatags()
	{
		if( is_object( $GLOBALS['TSFE'] ) && isset( $GLOBALS['TSFE']->config['config'] ) )
		{
			$GLOBALS['TSFE']->config['config']['disableCanonical'] = true;
			$GLOBALS['TSFE']->config['config']['noPageTitle'] = 2;
		}

		if( class_exists( '\TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry' )
			&& !\Aimeos\Aimeos\Base::getExtConfig( 'typo3Metatags', true )
		) {
			$registry = GeneralUtility::makeInstance( 'TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry' );

			$registry->getManagerForProperty( 'keywords' )->removeProperty( 'keywords' );
			$registry->getManagerForProperty( 'description' )->removeProperty( 'description' );
			$registry->getManagerForProperty( 'og:type' )->removeProperty( 'og:type' );
			$registry->getManagerForProperty( 'og:title' )->removeProperty( 'og:title' );
			$registry->getManagerForProperty( 'og:url' )->removeProperty( 'og:url' );
			$registry->getManagerForProperty( 'og:description' )->removeProperty( 'og:description' );
			$registry->getManagerForProperty( 'og:image' )->removeProperty( 'og:image' );
			$registry->getManagerForProperty( 'twitter:card' )->removeProperty( 'twitter:card' );
		}
	}
}
