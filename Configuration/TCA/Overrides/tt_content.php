<?php

if ( !defined( 'TYPO3_MODE' ) ) {
    die( 'Access denied.' );
}

$registerPlugins = function (array $plugins, string $pluginGroup) {
    foreach ( $plugins as $pluginName ) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_' . $pluginName] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            'aimeos_' . $pluginName,
            'FILE:EXT:aimeos/Configuration/FlexForms/' . str_replace( '-', '', ucwords( $pluginName, '-' ) ) . '.xml'
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Aimeos.aimeos',
            $pluginName,
            'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_' . $pluginName . '.title',
            'aimeos_'  .$pluginName,
            'Aimeos Shop: ' .$pluginGroup
        );
    }
};

$registerPlugins(
    [
    'jsonapi',
    'locale-select'
    ], 'Tools'
);
$registerPlugins(
    [
    'catalog-attribute',
    'catalog-count',
    'catalog-detail',
    'catalog-filter',
    'catalog-home',
    'catalog-list',
    'catalog-price',
    'catalog-search',
    'catalog-session',
    'catalog-stage',
    'catalog-stock',
    'catalog-suggest',
    'catalog-supplier',
    'catalog-tree',
    ],
    'Catalog'
);
$registerPlugins(
    [
    'basket-bulk',
    'basket-related',
    'basket-small',
    'basket-standard',
    ],
    'Basket'
);
$registerPlugins(
    [
    'checkout-confirm',
    'checkout-standard',
    'checkout-update',
    ],
    'Checkout'
);
$registerPlugins(
    [
    'account-download',
    'account-history',
    'account-favorite',
    'account-profile',
    'account-review',
    'account-subscription',
    'account-watch',
    ],
    'Account'
);

