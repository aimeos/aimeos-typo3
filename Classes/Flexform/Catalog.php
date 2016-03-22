<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Flexform;

use Aimeos\Aimeos\Base;


/**
 * Aimeos catalog flexform helper.
 *
 * @package TYPO3
 */
class Catalog
{
	/**
	 * Returns the list of categories with their ID.
	 *
	 * @param array $config Associative array of existing configurations
	 * @param \TYPO3\CMS\Backend\Form\FormEngine|\TYPO3\CMS\Backend\Form\DataPreprocessor $tceForms TCE forms object
	 * @param string $sitecode Unique code of the site to retrieve the categories for
	 * @return array Associative array with existing and new entries
	 */
	public function getCategories( array $config, $tceForms = null, $sitecode = 'default' )
	{
		if( isset( $config['flexParentDatabaseRow']['pid'] ) ) { // TYPO3 7+
			$pid = $config['flexParentDatabaseRow']['pid'];
		} elseif( isset( $config['row']['pid'] ) ) { // TYPO3 6.2
			$pid = $config['row']['pid'];
		}

		$pageTSConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getModTSconfig( $pid, 'tx_aimeos' );

		if( isset( $pageTSConfig['properties']['mshop.']['locale.']['site'] ) ) {
			$sitecode = $pageTSConfig['properties']['mshop.']['locale.']['site'];
		}

		try
		{
			$context = Base::getContext( Base::getConfig() );
			$context->setEditor( 'flexform' );

			$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $context );
			$context->setLocale( $localeManager->bootstrap( $sitecode, '', '', false ) );


			$manager = \Aimeos\MShop\Catalog\Manager\Factory::createManager( $context );
			$item = $manager->getTree( null, array(), \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE );


			$config['items'] = array_merge( $config['items'], $this->getCategoryList( $item, 0 ) );
		}
		catch( \Exception $e )
		{
			error_log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}

		return $config;
	}


	/**
	 * Returns the list of category label / ID pairs for the given node and all children
	 *
	 * @param MShop_Catalog_Item_Interface $item Catalog item to start from
	 * @param unknown_type $level Current level on indention
	 * @return array Associative array of category label / ID pairs
	 */
	protected function getCategoryList( \Aimeos\MShop\Catalog\Item\Iface $item, $level )
	{
		$result = array();
		$result[] = array( str_repeat( '.', $level * 4 ) . $item->getName(), $item->getId() );

		foreach( $item->getChildren() as $child ) {
			$result = array_merge( $result, $this->getCategoryList( $child, $level + 1 ) );
		}

		return $result;
	}
}
