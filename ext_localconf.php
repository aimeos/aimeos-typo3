<?php

if ( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


if( file_exists( __DIR__ . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once __DIR__ . '/Resources/Libraries/autoload.php';
}


/**
 * Include Aimeos extension directory
 */

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs']['0_aimeos'] = 'EXT:aimeos/Resources/Private/Config/';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['extDirs']['0_aimeos'] = 'EXT:aimeos/Resources/Private/Extensions/';


/**
 * Aimeos plugins
 */

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'locale-select',
	array( 'Locale' => 'select' ),
	array( 'Locale' => 'select' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-attribute',
	array( 'Catalog' => 'attribute' ),
	array( 'Catalog' => 'attribute' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-count',
	array( 'Catalog' => 'count' ),
	array( 'Catalog' => 'count' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-detail',
	array( 'Catalog' => 'detail' ),
	array( 'Catalog' => 'detail' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-filter',
	array( 'Catalog' => 'filter' ),
	array( 'Catalog' => 'filter' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-list',
	array( 'Catalog' => 'list' ),
	array( 'Catalog' => 'list' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-search',
	array( 'Catalog' => 'search' ),
	array( 'Catalog' => 'search' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-session',
	array( 'Catalog' => 'session' ),
	array( 'Catalog' => 'session' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stage',
	array( 'Catalog' => 'stage' ),
	array( 'Catalog' => 'stage' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stock',
	array( 'Catalog' => 'stock' ),
	array( 'Catalog' => 'stock' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-suggest',
	array( 'Catalog' => 'suggest' ),
	array( 'Catalog' => 'suggest' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-tree',
	array( 'Catalog' => 'tree' ),
	array( 'Catalog' => 'tree' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-related',
	array( 'Basket' => 'related' ),
	array( 'Basket' => 'related' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-small',
	array( 'Basket' => 'small' ),
	array( 'Basket' => 'small' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-standard',
	array( 'Basket' => 'index' ),
	array( 'Basket' => 'index' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-standard',
	array( 'Checkout' => 'index' ),
	array( 'Checkout' => 'index' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-confirm',
	array( 'Checkout' => 'confirm' ),
	array( 'Checkout' => 'confirm' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-update',
	array( 'Checkout' => 'update' ),
	array( 'Checkout' => 'update' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-download',
	array( 'Account' => 'download' ),
	array( 'Account' => 'download' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-history',
	array( 'Account' => 'history' ),
	array( 'Account' => 'history' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-favorite',
	array( 'Account' => 'favorite' ),
	array( 'Account' => 'favorite' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-profile',
	array( 'Account' => 'profile' ),
	array( 'Account' => 'profile' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-subscription',
	array( 'Account' => 'subscription' ),
	array( 'Account' => 'subscription' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-watch',
	array( 'Account' => 'watch' ),
	array( 'Account' => 'watch' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'jsonapi',
	array( 'Jsonapi' => 'index' ),
	array( 'Jsonapi' => 'index' )
);


/**
 * Aimeos scheduler tasks
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Typo6'] = array(
	'extension'        => 'aimeos',
	'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.name',
	'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Typo6',
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Email6'] = array(
	'extension'        => 'aimeos',
	'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.name',
	'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Email6',
);


/**
 * Add RealURL configuration
 * @deprecated Unused in TYPO9, removed for TYPO 10
 */

if( ( $aimeosExtConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['aimeos'] ) ) !== false
	&& isset( $aimeosExtConf['useRealUrlAutoConfig'] ) && $aimeosExtConf['useRealUrlAutoConfig'] != 0
) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['aimeos'] =
		'EXT:aimeos/Classes/Custom/Realurl.php:Aimeos\\Aimeos\\Custom\\Realurl->addAutoConfig';
}


/**
 * Avoid cHash for URLs with Aimeos parameters
 */
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = array_merge(
	$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'],
	[
		'ai[action]', 'ai[controller]',
		'ai[f_catid]', 'ai[f_name]', 'ai[f_search]', 'ai[f_sort]', 'ai[f_attrid]', 'ai[f_optid]', 'ai[f_oneid]',
		'ai[l_page]', 'ai[l_size]', 'ai[l_type]',
		'ai[d_prodid]', 'ai[d_name]', 'ai[d_pos]',
		'ai[b_action]', 'ai[b_attrvarid]', 'ai[b_attrconfid]', 'ai[b_attrcustid]', 'ai[b_coupon]', 'ai[b_position]', 'ai[b_prod]', 'ai[b_prodid]', 'ai[b_quantity]', 'ai[b_stocktype]',
		'ai[c_step]',
		'ai[sub_action]', 'ai[sub_id]',
		'ai[his_action]', 'ai[his_id]',
		'ai[fav_action]', 'ai[fav_id]', 'ai[fav_page]',
		'ai[pin_action]', 'ai[pin_id]',
		'ai[wat_action]', 'ai[wat_id]', 'ai[wat_page]',
		'ai[site]', 'ai[locale]', 'ai[currency]',
	]
);


/**
 * Add cache configuration
 */

if( !is_array( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] = array();
}

if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend';
}

if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] = array( 'defaultLifetime' => 0 );
}

if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] = array( 'pages' );
}


/**
 * Add Typo3 Hooks
 */

if( !isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] = \Aimeos\Aimeos\Base::class . '->logout';
}

?>