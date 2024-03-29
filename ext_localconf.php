<?php

defined('TYPO3') or die();


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('aimeos' );

if (file_exists($aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
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
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'locale-select',
    ['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select'],
    ['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-attribute',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-count',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-detail',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-filter',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-home',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-list',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-price',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-search',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-session',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-stage',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-stock',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-suggest',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-supplier',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'catalog-tree',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'basket-bulk',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'basket-related',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'related'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'related']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'basket-small',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'small']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'basket-standard',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'index']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'checkout-standard',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'checkout-confirm',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'checkout-update',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update']
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-basket',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'basket'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'basket']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-download',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'download'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'download']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-history',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'history'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'history']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-favorite',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-review',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'review'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'review']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-profile',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-subscription',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'account-watch',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'jsonapi',
    ['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index']
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'aimeos',
    'supplier-detail',
    ['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail'],
    ['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail']
);



/**
 * Aimeos scheduler tasks
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Typo6'] = [
    'extension'        => 'aimeos',
    'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.name',
    'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:default.description',
    'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Typo6'
];
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Email6'] = [
    'extension'        => 'aimeos',
    'title'            => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.name',
    'description'      => 'LLL:EXT:aimeos/Resources/Private/Language/scheduler.xlf:email.description',
    'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Email6'
];


/**
 * Avoid cHash for URLs with Aimeos parameters
 */
$GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = array_merge(
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'], [
        '^ai[', 'controller', 'action', 'code'
    ]
);


/**
 * Add cache configuration
 */

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] = [];
}

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
}

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] = ['defaultLifetime' => 0];
}

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] = ['pages'];
}


/**
 * Add TYPO3 Hooks
 */

// TYPO3 10, unnecessary in 11
if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['aimeos'] = \Aimeos\Aimeos\Base::class . '->logout';
}

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['aimeos'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['aimeos'] = \Aimeos\Aimeos\Base::class . '->clearCache';
}


/**
 * Add update wizard
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['aimeos'] = \Aimeos\Aimeos\Setup::class;


/**
 * Disable TYPO3 canonical tags so Aimeos ones are used
 */

if (!\Aimeos\Aimeos\Base::getExtConfig('typo3Canonical', false) ) {
    unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['canonical'] );
}


?>
