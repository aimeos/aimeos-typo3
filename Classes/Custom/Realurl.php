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
						array( 'GETvar' => 'ai[f_name]' ),
						array( 'GETvar' => 'ai[f_catid]' ),
					),
					's' => array(
						array( 'GETvar' => 'ai[f_sort]' ),
					),
					'l' => array(
						array( 'GETvar' => 'ai[l_page]' ),
						array( 'GETvar' => 'ai[l_size]' ),
					),
					'd' => array(
						array( 'GETvar' => 'ai[d_name]' ),
						array( 'GETvar' => 'ai[d_pos]' ),
						array( 'GETvar' => 'ai[d_prodid]' ),
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
						array( 'GETvar' => 'ai[d_name]' ),
					),
					'fav' => array(
						array( 'GETvar' => 'ai[fav_action]' ),
						array( 'GETvar' => 'ai[fav_id]' ),
						array( 'GETvar' => 'ai[d_name]' ),
					),
					'watch' => array(
						array( 'GETvar' => 'ai[wat_action]' ),
						array( 'GETvar' => 'ai[wat_id]' ),
						array( 'GETvar' => 'ai[d_name]' ),
					),
					'history' => array(
						array( 'GETvar' => 'ai[his_action]' ),
						array( 'GETvar' => 'ai[his_id]' ),
					),
					'bt' => array(
						array( 'GETvar' => 'ai[b_action]' ),
						array( 'GETvar' => 'ai[b_position]' ),
						array( 'GETvar' => 'ai[b_quantity]' ),
					),
					'co' => array(
						array( 'GETvar' => 'ai[c_step]' ),
					),
					'cc' => array(
						array( 'GETvar' => 'code' ),
					),
					'r' => array(
						array( 'GETvar' => 'ai[resource]' ),
						array( 'GETvar' => 'ai[id]' ),
						array( 'GETvar' => 'ai[related]' ),
						array( 'GETvar' => 'ai[relatedid]' ),
					),
					'js' => array(
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
