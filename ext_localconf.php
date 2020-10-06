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
if ( version_compare( TYPO3_version, '10.0.0', '<' ) ) {
	$localeController = 'Locale';
	$catalogController = 'Catalog';
	$basketController = 'Basket';
	$checkoutController = 'Checkout';
	$accountController = 'Account';
	$jsonController = 'Jsonapi';
} else {
	$localeController = \Aimeos\Aimeos\Controller\LocaleController::class;
	$catalogController = \Aimeos\Aimeos\Controller\CatalogController::class;
	$basketController = \Aimeos\Aimeos\Controller\BasketController::class;
	$checkoutController = \Aimeos\Aimeos\Controller\CheckoutController::class;
	$accountController = \Aimeos\Aimeos\Controller\AccountController::class;
	$jsonController = \Aimeos\Aimeos\Controller\JsonapiController::class;
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'locale-select',
	array( $localeController => 'select' ),
	array( $localeController => 'select' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-attribute',
	array( $catalogController => 'attribute' ),
	array( $catalogController => 'attribute' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-count',
	array( $catalogController => 'count' ),
	array( $catalogController => 'count' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-detail',
	array( $catalogController => 'detail' ),
	array( $catalogController => 'detail' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-filter',
	array( $catalogController => 'filter' ),
	array( $catalogController => 'filter' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-list',
	array( $catalogController => 'list' ),
	array( $catalogController => 'list' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-search',
	array( $catalogController => 'search' ),
	array( $catalogController => 'search' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-session',
	array( $catalogController => 'session' ),
	array( $catalogController => 'session' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stage',
	array( $catalogController => 'stage' ),
	array( $catalogController => 'stage' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-stock',
	array( $catalogController => 'stock' ),
	array( $catalogController => 'stock' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-suggest',
	array( $catalogController => 'suggest' ),
	array( $catalogController => 'suggest' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-supplier',
	array( $catalogController => 'supplier' ),
	array( $catalogController => 'supplier' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'catalog-tree',
	array( $catalogController => 'tree' ),
	array( $catalogController => 'tree' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-bulk',
	array( $basketController => 'bulk' ),
	array( $basketController => 'bulk' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-related',
	array( $basketController => 'related' ),
	array( $basketController => 'related' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-small',
	array( $basketController => 'small' ),
	array( $basketController => 'small' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'basket-standard',
	array( $basketController => 'index' ),
	array( $basketController => 'index' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-standard',
	array( $checkoutController => 'index' ),
	array( $checkoutController => 'index' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-confirm',
	array( $checkoutController => 'confirm' ),
	array( $checkoutController => 'confirm' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'checkout-update',
	array( $checkoutController => 'update' ),
	array( $checkoutController => 'update' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-download',
	array( $accountController => 'download' ),
	array( $accountController => 'download' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-history',
	array( $accountController => 'history' ),
	array( $accountController => 'history' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-favorite',
	array( $accountController => 'favorite' ),
	array( $accountController => 'favorite' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-profile',
	array( $accountController => 'profile' ),
	array( $accountController => 'profile' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-subscription',
	array( $accountController => 'subscription' ),
	array( $accountController => 'subscription' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'account-watch',
	array( $accountController => 'watch' ),
	array( $accountController => 'watch' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.aimeos',
	'jsonapi',
	array( $jsonController => 'index' ),
	array( $jsonController => 'index' )
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
$icons->registerIcon( 'aimeos_catalog-list', $provider, ['name' => 'list'] );
$icons->registerIcon( 'aimeos_catalog-detail', $provider, ['name' => 'cube'] );
$icons->registerIcon( 'aimeos_catalog-filter', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-attribute', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-search', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-supplier', $provider, ['name' => 'filter'] );
$icons->registerIcon( 'aimeos_catalog-suggest', $provider, ['name' => 'ellipsis-h'] );
$icons->registerIcon( 'aimeos_catalog-count', $provider, ['name' => 'bars'] );
$icons->registerIcon( 'aimeos_catalog-stage', $provider, ['name' => 'image'] );
$icons->registerIcon( 'aimeos_catalog-session', $provider, ['name' => 'thumb-tack'] );
$icons->registerIcon( 'aimeos_catalog-stock', $provider, ['name' => 'cubes'] );
$icons->registerIcon( 'aimeos_locale-select', $provider, ['name' => 'globe'] );
$icons->registerIcon( 'aimeos_account-history', $provider, ['name' => 'history'] );
$icons->registerIcon( 'aimeos_account-subscription', $provider, ['name' => 'repeat'] );
$icons->registerIcon( 'aimeos_account-favorite', $provider, ['name' => 'heart'] );
$icons->registerIcon( 'aimeos_account-watch', $provider, ['name' => 'eye'] );
$icons->registerIcon( 'aimeos_account-profile', $provider, ['name' => 'user'] );
$icons->registerIcon( 'aimeos_account-download', $provider, ['name' => 'download'] );
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
