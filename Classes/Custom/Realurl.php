<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;


/**
 * Aimeos RealURL configuraiton.
 *
 * @package TYPO3
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
		$params['config']['init']['emptySegmentValue'] = '';

		return array_merge_recursive( $params['config'], array(
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
					'f' => array(
						array( 'GETvar' => 'ai[f_catid]' ),
						array( 'GETvar' => 'ai[f_name]' ),
						array(
							'GETvar' => 'ai[f_sort]',
							'valueMap' => array(
								'code' => 'code',
								'-code' => '-code',
								'name' => 'name',
								'-name' => '-name',
								'price' => 'price',
								'-price' => '-price',
								'relevance' => 'relevance',
							),
							'noMatch' => 'bypass',
						),
						array( 'GETvar' => 'ai[l_page]' ),
					),
					'd' => array(
						array( 'GETvar' => 'ai[d_prodid]' ),
						array( 'GETvar' => 'ai[d_name]' ),
						array( 'GETvar' => 'ai[l_pos]' ),
					),
					'fs' => array(
						array( 'GETvar' => 'ai[f_search]' ),
					),
					'fa' => array(
						array( 'GETvar' => 'ai[f_attrid]' ),
					),
					'pin' => array(
						array( 'GETvar' => 'ai[pin_action]' ),
						array( 'GETvar' => 'ai[pin_id]' ),
					),
					'fav' => array(
						array( 'GETvar' => 'ai[fav_action]' ),
						array( 'GETvar' => 'ai[fav_id]' ),
					),
					'watch' => array(
						array( 'GETvar' => 'ai[wat_action]' ),
						array( 'GETvar' => 'ai[wat_id]' ),
					),
					'history' => array(
						array( 'GETvar' => 'ai[his_action]' ),
						array( 'GETvar' => 'ai[his_id]' ),
					),
					'bt' => array(
						array( 'GETvar' => 'ai[b_action]' ),
						array( 'GETvar' => 'ai[b_position]' ),
						array( 'GETvar' => 'ai[b_quantity]' ),
						array( 'GETvar' => 'ai[b_coupon]' ),
					),
					'co' => array(
						array( 'GETvar' => 'ai[c_step]' ),
					),
					'cc' => array(
						array( 'GETvar' => 'code' ),
						array( 'GETvar' => 'orderid' ),
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
