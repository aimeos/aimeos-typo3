<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

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
ExtensionUtility::configurePlugin(
    'aimeos',
    'LocaleSelect',
    ['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select'],
    ['Aimeos\\Aimeos\\Controller\\LocaleController' => 'select'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);


ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogAttribute',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'attribute'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogCount',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'count'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogDetail',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'detail'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogFilter',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'filter'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogHome',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'home'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogList',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'list'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogPrice',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'price'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogSearch',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'search'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogSession',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'session'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogStage',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stage'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogStock',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'stock'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogSuggest',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'suggest'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogSupplier',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'supplier'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CatalogTree',
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree'],
    ['Aimeos\\Aimeos\\Controller\\CatalogController' => 'tree'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'BasketBulk',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'bulk'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'BasketRelated',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'related'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'related'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'BasketSmall',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'small'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'small'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'BasketStandard',
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\BasketController' => 'index'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);


ExtensionUtility::configurePlugin(
    'aimeos',
    'CheckoutStandard',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'index'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CheckoutConfirm',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'confirm'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'CheckoutUpdate',
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update'],
    ['Aimeos\\Aimeos\\Controller\\CheckoutController' => 'update'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);


ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountBasket',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'basket'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'basket'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountDownload',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'download'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'download'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountHistory',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'history'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'history'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountFavorite',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'favorite'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountReview',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'review'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'review'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountProfile',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'profile'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountSubscription',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'subscription'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'AccountWatch',
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch'],
    ['Aimeos\\Aimeos\\Controller\\AccountController' => 'watch'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'Jsonapi',
    ['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index'],
    ['Aimeos\\Aimeos\\Controller\\JsonapiController' => 'index'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'aimeos',
    'SupplierDetail',
    ['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail'],
    ['Aimeos\\Aimeos\\Controller\\SupplierController' => 'detail'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
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

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] ??= 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['options'] ??= ['defaultLifetime' => 0];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] ??= ['pages'];
