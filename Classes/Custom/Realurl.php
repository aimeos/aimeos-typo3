<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2017
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
	/** @var \TYPO3\CMS\Core\Database\DatabaseConnection */
	protected $databaseConnection;

	/**
	 * Initializes the class.
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

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

		$this->addSites( $params['config'] );
		$this->addLanguages( $params['config'] );
		$this->addCurrencies( $params['config'] );

		return array_merge_recursive( $params['config'], array(
			'preVars' => array(
				array(
					'GETvar' => 'no_cache',
					'valueMap' => array(
						'nc' => '1',
					),
					'noMatch' => 'bypass'
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
						array( 'GETvar' => 'ai[d_pos]' ),
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

	/**
	 * Adds sites to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addSites(array &$configuration) {
		$sites = $this->databaseConnection->exec_SELECTgetRows('t1.id AS id,t1.code AS code', 'mshop_locale_site t1', 't1.status=1');

		if (count($sites) > 1) {
			$configuration['preVars']['ai_site'] = array(
				'GETvar' => 'S',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach ($sites as $site) {
				$configuration['preVars']['ai_site']['valueMap'][strtolower($site['code'])] = $site['id'];
			}
		}
	}

	/**
	 * Adds languages to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addLanguages(array &$configuration) {
		$languages = $this->databaseConnection->exec_SELECTgetRows('t1.langid AS id', 'mshop_locale t1', 't1.status=1', '', 't1.pos');

		if (count($languages) > 1) {
			$configuration['preVars']['ai_language'] = array(
				'GETvar' => 'ai[loc_language]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach ($languages as $language) {
				$configuration['preVars']['ai_language']['valueMap'][strtolower($language['id'])] = $language['id'];
			}
		}
	}

	/**
	 * Adds currencies to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addCurrencies(array &$configuration) {
		$currencies = $this->databaseConnection->exec_SELECTgetRows('t1.currencyid AS id', 'mshop_locale t1', 't1.status=1', '', 't1.pos');

		if (count($currencies) > 1) {
			/*$configuration['preVars']['ai_currency'] = array(
				'GETvar' => 'ai[loc_currency]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);*/
			$configuration['postVarSets']['_DEFAULT']['aimeos']['ai_currency'] = array(
				'GETvar' => 'ai[loc_currency]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach ($currencies as $currency) {
				/*$configuration['preVars']['ai_currency']['valueMap'][strtolower($currency['id'])] = $currency['id'];*/
				$configuration['postVarSets']['_DEFAULT']['aimeos']['ai_currency']['valueMap'][strtolower($currency['id'])] = $currency['id'];
			}
		}
	}
}
