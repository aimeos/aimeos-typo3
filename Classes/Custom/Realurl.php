<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Custom;


/**
 * Aimeos RealURL configuraiton.
 *
 * @package TYPO3_Aimeos
 */
class Realurl
{
	/**
	 * Generates additional RealURL configuration and merges it with provided configuration
	 *
	 * @param array $params Default configuration
	 * @param tx_realurl_autoconfgen $pObj Parent object
	 * @return array Updated configuration
	 */
	public function addAutoConfig( $params, &$pObj )
	{
		$params['config']['init']['emptySegmentValue'] = '-';

		return array_merge_recursive( $params['config'], array(
			'preVars' => array(
				array(
					'GETvar' => 'no_cache',
						'valueMap' => array(
						'nc' => 1,
					),
					'noMatch' => 'bypass',
				),
			),
			'postVarSets' => array(
				'_DEFAULT' => array(
					'aimeos' => array(
						array(
							'GETvar' => 'ai[controller]',
							'noMatch' => 'bypass',
						),
						array(
							'GETvar' => 'ai[action]',
							'noMatch' => 'bypass',
						),
					),
					'l' => array(
						array( 'GETvar' => 'ai[f-catalog-id]' ),
						array( 'GETvar' => 'ai[l-page]' ),
						array(
							'GETvar' => 'ai[f-sort]',
							'valueMap' => array(
								'name' => 'name',
								'-name' => '-name',
								'price' => 'price',
								'-price' => '-price',
								'relevance' => 'relevance',
							),
							'noMatch' => 'bypass',
						),
					),
					'd' => array(
						array( 'GETvar' => 'ai[d-product-id]' ),
						array( 'GETvar' => 'ai[l-pos]' ),
					),
					'f' => array(
						array( 'GETvar' => 'ai[f-search-text]' ),
					),
					'n' => array(
						array( 'GETvar' => 'ai[a-name]' ),
					),
					'pin' => array(
						array( 'GETvar' => 'ai[pin-action]' ),
						array( 'GETvar' => 'ai[pin-id]' ),
					),
					'fav' => array(
						array( 'GETvar' => 'ai[fav-action]' ),
						array( 'GETvar' => 'ai[fav-id]' ),
					),
					'watch' => array(
						array( 'GETvar' => 'ai[watch-action]' ),
						array( 'GETvar' => 'ai[watch-id]' ),
					),
					'bt' => array(
						array( 'GETvar' => 'ai[b-action]' ),
						array( 'GETvar' => 'ai[b-position]' ),
						array( 'GETvar' => 'ai[b-quantity]' ),
						array( 'GETvar' => 'ai[b-coupon]' ),
					),
					'co' => array(
						array( 'GETvar' => 'ai[c-step]' ),
					),
					'json' => array(
						'type' => 'single',
						'keyValues' => array(
							'type' => 191351524
						),
					),
					'plain' => array(
						'type' => 'single',
						'keyValues' => array(
							'type' => 191351525
						),
					),
				),
			),
		) );
	}
}
