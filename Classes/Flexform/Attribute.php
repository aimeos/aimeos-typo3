<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Flexform;

use Aimeos\Aimeos\Base;


/**
 * Aimeos attribute flexform helper
 *
 * @package TYPO3
 */
class Attribute
{
	/**
	 * Returns the list of attribute types with their ID
	 *
	 * @param array $config Associative array of existing configurations
	 * @param \TYPO3\CMS\Backend\Form\FormEngine|\TYPO3\CMS\Backend\Form\DataPreprocessor $tceForms TCE forms object
	 * @param string $sitecode Unique code of the site to retrieve the categories for
	 * @return array Associative array with existing and new entries
	 */
	public function getTypes( array $config, $tceForms = null, string $sitecode = 'default' ) : array
	{
		try
		{
			if( !isset( $config['flexParentDatabaseRow']['pid'] ) ) {
				throw new \Exception( 'No PID found in "flexParentDatabaseRow" or "row" array key: ' . print_r( $config, true ) );
			}

			$pid = $config['flexParentDatabaseRow']['pid'];
			$pageTSConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig( $pid );

			if( isset( $pageTSConfig['tx_aimeos.']['mshop.']['locale.']['site'] ) ) {
				$sitecode = $pageTSConfig['tx_aimeos.']['mshop.']['locale.']['site'];
			}

			$context = Base::getContext( Base::getConfig() );
			$context->setEditor( 'flexform' );

			$localeManager = \Aimeos\MShop::create( $context, 'locale' );
			$context->setLocale( $localeManager->bootstrap( $sitecode, '', '', false ) );

			$manager = \Aimeos\MShop::create( $context, 'attribute/type' );
			$items = $manager->search( $manager->filter( true ) );

			foreach( $items as $item ) {
				$config['items'][] = [$item->getName(), $item->getCode()];
			}
		}
		catch( \Exception $e )
		{
			error_log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}

		return $config;
	}
}
