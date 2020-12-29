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
$prefix = $suffix = '';
if( version_compare( TYPO3_version, '10.0.0', '>=' ) ) {
	$prefix = 'Aimeos\\Aimeos\\Controller\\';
	$suffix = 'Controller';
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'locale-select',
	[$prefix . 'Locale' . $suffix => 'select'],
	[$prefix . 'Locale' . $suffix => 'select']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-attribute',
	[$prefix . 'Catalog' . $suffix => 'attribute'],
	[$prefix . 'Catalog' . $suffix => 'attribute']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-count',
	[$prefix . 'Catalog' . $suffix => 'count'],
	[$prefix . 'Catalog' . $suffix => 'count']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-detail',
	[$prefix . 'Catalog' . $suffix => 'detail'],
	[$prefix . 'Catalog' . $suffix => 'detail']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-filter',
	[$prefix . 'Catalog' . $suffix => 'filter'],
	[$prefix . 'Catalog' . $suffix => 'filter']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-home',
	[$prefix . 'Catalog' . $suffix => 'home'],
	[$prefix . 'Catalog' . $suffix => 'home']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-list',
	[$prefix . 'Catalog' . $suffix => 'list'],
	[$prefix . 'Catalog' . $suffix => 'list']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-price',
	[$prefix . 'Catalog' . $suffix => 'price'],
	[$prefix . 'Catalog' . $suffix => 'price']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-search',
	[$prefix . 'Catalog' . $suffix => 'search'],
	[$prefix . 'Catalog' . $suffix => 'search']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-session',
	[$prefix . 'Catalog' . $suffix => 'session'],
	[$prefix . 'Catalog' . $suffix => 'session']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stage',
	[$prefix . 'Catalog' . $suffix => 'stage'],
	[$prefix . 'Catalog' . $suffix => 'stage']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stock',
	[$prefix . 'Catalog' . $suffix => 'stock'],
	[$prefix . 'Catalog' . $suffix => 'stock']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-suggest',
	[$prefix . 'Catalog' . $suffix => 'suggest'],
	[$prefix . 'Catalog' . $suffix => 'suggest']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-supplier',
	[$prefix . 'Catalog' . $suffix => 'supplier'],
	[$prefix . 'Catalog' . $suffix => 'supplier']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-tree',
	[$prefix . 'Catalog' . $suffix => 'tree'],
	[$prefix . 'Catalog' . $suffix => 'tree']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-bulk',
	[$prefix . 'Basket' . $suffix => 'bulk'],
	[$prefix . 'Basket' . $suffix => 'bulk']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-related',
	[$prefix . 'Basket' . $suffix => 'related'],
	[$prefix . 'Basket' . $suffix => 'related']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-small',
	[$prefix . 'Basket' . $suffix => 'small'],
	[$prefix . 'Basket' . $suffix => 'small']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-standard',
	[$prefix . 'Basket' . $suffix => 'index'],
	[$prefix . 'Basket' . $suffix => 'index']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-standard',
	[$prefix . 'Checkout' . $suffix => 'index'],
	[$prefix . 'Checkout' . $suffix => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-confirm',
	[$prefix . 'Checkout' . $suffix => 'confirm'],
	[$prefix . 'Checkout' . $suffix => 'confirm']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-update',
	[$prefix . 'Checkout' . $suffix => 'update'],
	[$prefix . 'Checkout' . $suffix => 'update']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-download',
	[$prefix . 'Account' . $suffix => 'download'],
	[$prefix . 'Account' . $suffix => 'download']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-history',
	[$prefix . 'Account' . $suffix => 'history'],
	[$prefix . 'Account' . $suffix => 'history']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-favorite',
	[$prefix . 'Account' . $suffix => 'favorite'],
	[$prefix . 'Account' . $suffix => 'favorite']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-review',
	[$prefix . 'Account' . $suffix => 'review'],
	[$prefix . 'Account' . $suffix => 'review']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-profile',
	[$prefix . 'Account' . $suffix => 'profile'],
	[$prefix . 'Account' . $suffix => 'profile']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-subscription',
	[$prefix . 'Account' . $suffix => 'subscription'],
	[$prefix . 'Account' . $suffix => 'subscription']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-watch',
	[$prefix . 'Account' . $suffix => 'watch'],
	[$prefix . 'Account' . $suffix => 'watch']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'jsonapi',
	[$prefix . 'Jsonapi' . $suffix => 'index'],
	[$prefix . 'Jsonapi' . $suffix => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'supplier-detail',
	[$prefix . 'Supplier' . $suffix => 'detail'],
	[$prefix . 'Supplier' . $suffix => 'detail']
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
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Typo6',
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Email6'] = array(
	'extension'        => 'aimeos',
	'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.name',
	'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Email6',
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
