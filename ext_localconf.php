<?php

if( !defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );

if( file_exists( $aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}


/**
 * Include Aimeos extension directory
 */

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['confDirs']['0_aimeos'] = 'EXT:aimeos/Resources/Private/Config/';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['extDirs']['0_aimeos'] = 'EXT:aimeos/Resources/Private/Extensions/';


/**
 * Aimeos plugins
 */
$name = defined( 'TYPO3_version' ) && version_compare( constant( 'TYPO3_version' ), '11.0.0', '<' ) ? 'Aimeos.' : '';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'locale-select',
	['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select'],
	['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-attribute',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-count',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-detail',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-filter',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-home',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-list',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-price',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-search',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-session',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-stage',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-stock',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-suggest',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-supplier',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'catalog-tree',
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree'],
	['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'basket-bulk',
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk'],
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'basket-related',
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'related'],
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'related']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'basket-small',
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'small'],
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'small']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'basket-standard',
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'index'],
	['Aimeos\\Aimeos\\Controller\\BasketController' => 'index']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'checkout-standard',
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index'],
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'checkout-confirm',
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm'],
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'checkout-update',
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update'],
	['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-download',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'download'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'download']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-history',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'history'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'history']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-favorite',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-review',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'review'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'review']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-profile',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-subscription',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'account-watch',
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch'],
	['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'jsonapi',
	['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index'],
	['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$name . 'aimeos',
	'supplier-detail',
	['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail'],
	['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail']
);


/**
 * Register Aimeos content elements
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
	'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:aimeos/Configuration/PageTS/aimeos.tsconfig">'
);

/**
 * Register icons for Aimeos content elements
 */
$provider = 'TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider';
$icons = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Core\Imaging\IconRegistry::class );
$icons->registerIcon( 'aimeos_catalog-home', $provider, ['name' => 'globe'] );
$icons->registerIcon( 'aimeos_catalog-list', $provider, ['name' => 'list'] );
$icons->registerIcon( 'aimeos_catalog-detail', $provider, ['name' => 'cube'] );
$icons->registerIcon( 'aimeos_catalog-filter', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-attribute', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-price', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-search', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-supplier', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-suggest', $provider, ['name' => 'ellipsis-h'] );
$icons->registerIcon( 'aimeos_catalog-count', $provider, ['name' => 'bars'] );
$icons->registerIcon( 'aimeos_catalog-stage', $provider, ['name' => 'image'] );
$icons->registerIcon( 'aimeos_catalog-session', $provider, ['name' => 'thumb-tack'] );
$icons->registerIcon( 'aimeos_catalog-stock', $provider, ['name' => 'cubes'] );
$icons->registerIcon( 'aimeos_supplier-detail', $provider, ['name' => 'industry'] );
$icons->registerIcon( 'aimeos_locale-select', $provider, ['name' => 'globe'] );
$icons->registerIcon( 'aimeos_account-download', $provider, ['name' => 'download'] );
$icons->registerIcon( 'aimeos_account-history', $provider, ['name' => 'history'] );
$icons->registerIcon( 'aimeos_account-favorite', $provider, ['name' => 'heart'] );
$icons->registerIcon( 'aimeos_account-profile', $provider, ['name' => 'user'] );
$icons->registerIcon( 'aimeos_account-review', $provider, ['name' => 'comments'] );
$icons->registerIcon( 'aimeos_account-subscription', $provider, ['name' => 'repeat'] );
$icons->registerIcon( 'aimeos_account-watch', $provider, ['name' => 'eye'] );
$icons->registerIcon( 'aimeos_basket-standard', $provider, ['name' => 'shopping-cart'] );
$icons->registerIcon( 'aimeos_basket-small', $provider, ['name' => 'shopping-basket'] );
$icons->registerIcon( 'aimeos_basket-related', $provider, ['name' => 'link'] );
$icons->registerIcon( 'aimeos_basket-bulk', $provider, ['name' => 'truck'] );
$icons->registerIcon( 'aimeos_checkout-standard', $provider, ['name' => 'credit-card'] );
$icons->registerIcon( 'aimeos_checkout-confirm', $provider, ['name' => 'check'] );
$icons->registerIcon( 'aimeos_checkout-update', $provider, ['name' => 'euro'] );
$icons->registerIcon( 'aimeos_jsonapi', $provider, ['name' => 'code'] );

$icons->registerIcon( 'aimeos-widget-latestorders', $provider, ['name' => 'shopping-cart'] );


/**
 * Aimeos scheduler tasks
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Typo6'] = array(
	'extension'        => 'aimeos',
	'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.name',
	'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Typo6'
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Email6'] = array(
	'extension'        => 'aimeos',
	'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.name',
	'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Email6'
);


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
		'ai[resource]', 'ai[include]', 'ai[related]', 'ai[id]', 'ai[filter]',
		'ai[fields]', 'ai[page]', 'ai[sort]',
	]
);


/**
 * Add cache configuration
 */

if( !is_array( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] = array();
}

if( !isset( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
}

if( !isset( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] = array( 'defaultLifetime' => 0 );
}

if( !isset( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] = array( 'pages' );
}


/**
 * Add TYPO3 Hooks
 */

// TYPO3 10, unnecessary in 11
if( !isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] = \Aimeos\Aimeos\Base::class . '->logout';
}

if( !isset( $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['aimeos'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['aimeos'] = function( array $cacheType, $dataHandler ) {
		\Aimeos\Aimeos\Base::clearCache( $cacheType );
	};
}


/**
 * Disable TYPO3 canonical tags so Aimeos ones are used
 */

if( !\Aimeos\Aimeos\Base::getExtConfig( 'typo3Canonical', false ) ) {
	unset( $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['canonical'] );
}

?>
