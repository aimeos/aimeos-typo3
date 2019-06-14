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
 * Aimeos product flexform helper.
 *
 * @package TYPO3
 */
class Product
{
	/**
	 * Returns the list of products with their code.
	 *
	 * @param array $config Associative array of existing configurations
	 * @param \TYPO3\CMS\Backend\Form\FormEngine|\TYPO3\CMS\Backend\Form\DataPreprocessor $tceForms TCE forms object
	 * @param string $sitecode Unique code of the site to retrieve the categories for
	 * @return array Associative array with existing and new entries
	 */
	public function getProducts( array $config, $tceForms = null, $sitecode = 'default' )
	{
		try {
			if( isset( $config['flexParentDatabaseRow']['pid'] ) ) { // TYPO3 7+
				$pid = $config['flexParentDatabaseRow']['pid'];
			} elseif( isset( $config['row']['pid'] ) ) { // TYPO3 6.2
				$pid = $config['row']['pid'];
			} else {
				throw new \Exception( 'No PID found in "flexParentDatabaseRow" or "row" array key: ' . print_r( $config, true ) );
			}

			$pageTSConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getModTSconfig( $pid, 'tx_aimeos' );

			if( isset( $pageTSConfig['properties']['mshop.']['locale.']['site'] ) ) {
				$sitecode = $pageTSConfig['properties']['mshop.']['locale.']['site'];
			}

			$context = Base::getContext( Base::getConfig() );
			$context->setEditor( 'flexform' );

			$localeManager = \Aimeos\MShop::create( $context, 'locale' );
			$context->setLocale( $localeManager->bootstrap( $sitecode, '', '', false ) );

			$manager = \Aimeos\MShop::create( $context, 'product' );
			$search = $manager->createSearch( true );
			$search->setSlice( 0, 10000 );
			$search->setSortations( [$search->sort( '+', 'product.label' )] );

			$products = $manager->searchItems( $search );

			$config['items'] = array_map( function( $product ) {
				return [$product->getName(), $product->getCode()];
			}, $products );
		} catch( \Exception $e ) {
			error_log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}

		return $config;
	}
}
