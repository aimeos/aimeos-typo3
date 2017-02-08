<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Gilbertsoft (gilbertsoft.org), 2017
 * @copyright Aimeos (aimeos.org), 2017-
 * @package TYPO3
 */

namespace Aimeos\Aimeos\Custom;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Hooks for DataHandler
 *
 * @package TYPO3
 */
class DataHandler
{

	/**
	 * Templates, please insert in alphabetical order if used!
	 *
	 * public function processCmdmap_preProcess( $command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 * public function processCmdmap_postProcess( $command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 * public function processCmdmap_deleteAction( $table, $id, $recordToDelete, $recordWasDeleted=NULL, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 * public function processDatamap_preProcessFieldArray( array &$fieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 * public function processDatamap_afterAllOperations( \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 * public function processDatamap_afterDatabaseOperations( $status, $table, $id, array $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj ) {}
	 *
	 */

	/**
	* Checks if the fields defined in $deprecatedFields are set in the data-array of pi_flexform and unsets it.
	*
	* Structure of the deprecatedFields array:
	*
	* [ 'list_type' => ['field1', 'field2'] ];
	*
	* @param string $status
	* @param string $table
	* @param string $id
	* @param array $fieldArray
	* @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
	*
	* @return void
	*/
	public function processDatamap_postProcessFieldArray( $status, $table, $id, array &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj )
	{
		// Only listen to flexform data
		if( $table === 'tt_content' && $status === 'update' && isset( $fieldArray['pi_flexform'] ) )
		{
			// Get list type
			$listType = isset( $fieldArray['list_type'] ) ? $fieldArray['list_type'] : '';

			if( empty( $listType ) )
			{
				$record = BackendUtility::getRecord( $table, $id, 'list_type' );
				$listType = $record['list_type'];
			}


			// Only listen to own plugin data
			if( !empty( $listType ) && compare( $listType, 'aimeos_', 1) == 0 )
			{
				// Define deprecated fields which should be ignored on saving
				$deprecatedFields = array(
					/*
					// Links to deprecated plugins
					'aimeos_catalog-detail' => array(
						'settings.client.html.catalog.stock.url.target',
					),
					'aimeos_catalog-filter' => array(
						'settings.client.html.catalog.count.url.target',
						'settings.client.html.catalog.suggest.url.target',
					),
					'aimeos_catalog-list' => array(
						'settings.client.html.catalog.stock.url.target',
					),
					'aimeos_checkout-standard' => array(
						'settings.client.html.checkout.update.url.target',
					),

					// Deprecated plugins
					'aimeos_catalog-count' => array(
						'settings.typo3.tsconfig',
					),
					'aimeos_catalog-stock' => array(
						'settings.typo3.tsconfig',
					),
					'aimeos_catalog-suggest' => array(
						'settings.client.html.catalog.detail.url.target',
						'settings.typo3.tsconfig',
					),
					'aimeos_checkout-update' => array(
						'settings.client.html.checkout.confirm.url.target',
						'settings.typo3.tsconfig',
					),
					*/
				);


				// Get array from flex from xml
				$flexformData = GeneralUtility::xml2array( $fieldArray['pi_flexform'] );

				// Search for deprecated plugins fields
				foreach( $deprecatedFields as $plugin => $fields )
				{
					if( $listType === $plugin )
					{
						// Search for deprecated fields and delete
						foreach( $fields as $field )
						{
							if ( isset( $flexformData['data']['sDEF']['lDEF'][$field] ) )
							{
								unset( $flexformData['data']['sDEF']['lDEF'][$field] );
							}
						}

						// Remove empty sheet
						if ( isset( $flexformData['data']['sDEF']['lDEF'] ) && $flexformData['data']['sDEF']['lDEF'] === array() )
						{
							unset( $flexformData['data']['sDEF'] );
						}
					}
				}


				// Search for empty fields and delete
				foreach( $flexformData['data']['sDEF']['lDEF'] as $field => $values )
				{
					if ( isset( $values['vDEF'] ) && $values['vDEF'] === '' )
					{
						unset( $flexformData['data']['sDEF']['lDEF'][$field] );
					}

					// Remove empty sheet
					if ( isset( $flexformData['data']['sDEF']['lDEF'] ) && $flexformData['data']['sDEF']['lDEF'] === array() )
					{
						unset( $flexformData['data']['sDEF'] );
					}
				}


				// Save back to field array
				$flexFormTools = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools' );
				$fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml( $flexformData, true );
			}
		}
	}

}
