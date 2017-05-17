<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2017
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Custom;


use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Aimeos\Aimeos\Base;


/**
 * Aimeos RealURL configuraiton.
 *
 * @package TYPO3
 */
class Realurl
{
	/** @var \Aimeos\Aimeos\Base\Context */
	private static $context;

	/** @var \TYPO3\CMS\Core\Database\DatabaseConnection */
	protected $databaseConnection;

	/** @var bool */
	protected $hasStaticInfoTables;

	/** @var string */
	protected $extKey;


	/**
	 * Initializes the class.
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		$this->hasStaticInfoTables = ExtensionManagementUtility::isLoaded('static_info_tables');
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
		$extKey = $params['extKey'];

		$params['config']['init']['emptySegmentValue'] = '';

		$this->addNoCache( $params );
		$this->addSites( $params );
		$this->addLanguages( $params );
		$this->addCurrencies( $params );

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
					),
					's' => array(
						array( 'GETvar' => 'ai[f_sort]' ),
					),
					'l' => array(
						array( 'GETvar' => 'ai[l_page]' ),
						array( 'GETvar' => 'ai[l_size]' ),
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
					),
					'co' => array(
						array( 'GETvar' => 'ai[c_step]' ),
					),
					'cc' => array(
						array( 'GETvar' => 'code' ),
						array( 'GETvar' => 'orderid' ),
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


	/**
	 * Verifies RealURL configuration and adds missing configuration
	 *
	 * @param array $params Default configuration
	 * @param tx_realurl_autoconfgen $pObj Parent object
	 * @return void
	 */
	public function verifyAutoConfig( $params, &$pObj )
	{
		//$this->addNoCache( $params );
	}


	/**
	 * Adds no_cache to configuration if not already defined
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addNoCache(array &$configuration) {
		if( (bool) Base::getExtConfig( 'realUrlAddNoCache', false ) === false ) {
			return;
		}

		$nocache = ArrayUtility::filterByValueRecursive( 'no_cache', $configuration );
		if ( isset( $nocache ) && !empty( $nocache ) ) {
			return;
		}

		$configuration['config']['preVars'] = array_merge_recursive( array( 0 => array(
			'GETvar' => 'no_cache',
			'valueMap' => array(
				'nc' => '1',
			),
			'noMatch' => 'bypass',
		) ), $configuration['config']['preVars'] );
	}


	/**
	 * Adds sites to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addSites( array &$configuration ) {
		//$sites = $this->databaseConnection->exec_SELECTgetRows('t1.id AS id,t1.code AS code', 'mshop_locale_site t1', 't1.status=1');
		$sites = $this->getAvailableSites();

		if( count( $sites ) > 1 ) {
			$configuration['config']['preVars']['ai_site'] = array(
				'GETvar' => 'ai[site]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach( $sites as $site ) {
				$code = $site->getCode();
				$configuration['config']['preVars']['ai_site']['valueMap'][strtolower( $code )] = $code;
			}
		}
	}


	/**
	 * Adds languages to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addLanguages( array &$configuration ) {
		//$languages = $this->databaseConnection->exec_SELECTgetRows('t1.langid AS id', 'mshop_locale t1', 't1.status=1', '', 't1.pos');
		$languages = $this->getAvailableLocales();

		if( count( $languages ) > 1 ) {
			$configuration['config']['preVars']['ai_locale'] = array(
				'GETvar' => 'ai[locale]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach( $languages as $language ) {
				$langid = $language->getLanguageId();
				$configuration['config']['preVars']['ai_locale']['valueMap'][strtolower( $langid )] = $langid;
			}
		}

		/*
		if ($this->hasStaticInfoTables) {
			$languages = $this->databaseConnection->exec_SELECTgetRows('t1.uid AS uid,t2.lg_collate_locale AS lg_collate_locale', 'sys_language t1, static_languages t2', 't2.uid=t1.static_lang_isocode AND t1.hidden=0 AND t2.lg_iso_2<>\'\'');
		}
		else {
			$languages = array();
		}
		if (count($languages) > 0) {
			$configuration['config']['preVars'][0] = array(
				'GETvar' => 'L',
				'valueMap' => array(
				),
				'noMatch' => 'bypass'
			);
			foreach ($languages as $lang) {
				$configuration['config']['preVars'][0]['valueMap'][strtolower($lang['lg_collate_locale'])] = $lang['uid'];
			}
		}
		*/
	}


	/**
	 * Adds currencies to configuration
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function addCurrencies(array &$configuration) {
		//$currencies = $this->databaseConnection->exec_SELECTgetRows('t1.currencyid AS id', 'mshop_locale t1', 't1.status=1', '', 't1.pos');
		$currencies = $this->getAvailableLocales();

		if ( count( $currencies ) > 1 ) {
			/*$configuration['config']['preVars']['ai_currency'] = array(
				'GETvar' => 'ai[currency]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);*/
			$configuration['config']['postVarSets']['_DEFAULT']['aimeos']['ai_currency'] = array(
				'GETvar' => 'ai[currency]',
				'valueMap' => array(
				),
				'noMatch' => 'bypass',
			);
			foreach ( $currencies as $currency ) {
				$currencyid = $currency->getCurrencyId();
				/*$configuration['config']['preVars']['ai_currency']['valueMap'][strtolower($currency['id'])] = $currency['id'];*/
				$configuration['config']['postVarSets']['_DEFAULT']['aimeos']['ai_currency']['valueMap'][strtolower( $currencyid )] = $currencyid;
			}
		}
	}


	/**
	 * Returns the list of sites.
	 *
	 * @return array Associative list of items and children implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 */
	protected function getAvailableSites()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'locale/site' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'locale.site.level', 0 ) );
		$search->setConditions( $search->compare( '==', 'locale.site.status', 1 ) );
		$search->setSortations( array( $search->sort( '+', 'locale.site.label' ) ) );

		$sites = $manager->searchItems( $search );

		return $sites;
	}


	/**
	 * Returns the list of locales.
	 *
	 * @return array Associative list of items and children implementing \Aimeos\MShop\Locale\Item\Site\Iface
	 */
	protected function getAvailableLocales()
	{
		static $locales = null;

		if ($locales === null) {
			$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'locale' );

			$search = $manager->createSearch();
			$search->setConditions( $search->compare( '==', 'locale.status', 1 ) );
			$search->setSortations( array( $search->sort( '+', 'locale.position' ) ) );

			$locales = $manager->searchItems( $search );
		}

		return $locales;
	}


	/**
	 * Returns the context item for the frontend
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function getContext()
	{
		$config = Base::getConfig();

		if( !isset( self::$context ) )
		{
			$context = Base::getContext( $config );
			$locale = Base::getLocale( $context );
			//$context->setI18n( Base::getI18n( array( $locale->getLanguageId() ), $config->get( 'i18n', array() ) ) );
			$context->setLocale( $locale );

			self::$context = $context;
		}

		//$langid = self::$context->getLocale()->getLanguageId();
		//$templatePaths = Base::getAimeos()->getCustomPaths( 'client/html/templates' );
		//self::$context->setView( Base::getView( $config, $this->uriBuilder, $templatePaths, $this->request, $langid ) );

		return self::$context;
	}
}
