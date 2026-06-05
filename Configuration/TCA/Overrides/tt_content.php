<?php

defined('TYPO3') or die();


$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'Jsonapi',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_jsonapi.title',
    'aimeos_jsonapi',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_jsonapi.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/Jsonapi.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'LocaleSelect',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_locale-select.title',
    'aimeos_locale-select',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_locale-select.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/LocaleSelect.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogAttribute',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-attribute.title',
    'aimeos_catalog-attribute',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-attribute.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogAttribute.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogCount',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-count.title',
    'aimeos_catalog-count',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-count.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogCount.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogDetail',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-detail.title',
    'aimeos_catalog-detail',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-detail.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogDetail.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogFilter',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-filter.title',
    'aimeos_catalog-filter',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-filter.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogFilter.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogHome',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-home.title',
    'aimeos_catalog-home',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-home.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogHome.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogList',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-list.title',
    'aimeos_catalog-list',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-list.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogList.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogPrice',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-price.title',
    'aimeos_catalog-price',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-price.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogPrice.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogSearch',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-search.title',
    'aimeos_catalog-search',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-search.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSearch.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogSession',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-session.title',
    'aimeos_catalog-session',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-session.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSession.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogStage',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-stage.title',
    'aimeos_catalog-stage',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-stage.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogStage.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogStock',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-stock.title',
    'aimeos_catalog-stock',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-stock.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogStock.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogSuggest',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-suggest.title',
    'aimeos_catalog-suggest',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-suggest.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSuggest.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogSupplier',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-supplier.title',
    'aimeos_catalog-supplier',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-supplier.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSupplier.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CatalogTree',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-tree.title',
    'aimeos_catalog-tree',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_catalog-tree.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CatalogTree.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'BasketBulk',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-bulk.title',
    'aimeos_basket-bulk',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-bulk.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/BasketBulk.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'BasketRelated',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-related.title',
    'aimeos_basket-related',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-related.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/BasketRelated.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'BasketSmall',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-small.title',
    'aimeos_basket-small',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-small.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/BasketSmall.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'BasketStandard',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-standard.title',
    'aimeos_basket-standard',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_basket-standard.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/BasketStandard.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CheckoutConfirm',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-confirm.title',
    'aimeos_checkout-confirm',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-confirm.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutConfirm.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CheckoutStandard',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-standard.title',
    'aimeos_checkout-standard',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-standard.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutStandard.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'CheckoutUpdate',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-update.title',
    'aimeos_checkout-update',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_checkout-update.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutUpdate.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountBasket',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-basket.title',
    'aimeos_account-basket',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-basket.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountBasket.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountDownload',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-download.title',
    'aimeos_account-download',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-download.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountDownload.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountHistory',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-history.title',
    'aimeos_account-history',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-history.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountHistory.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountFavorite',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-favorite.title',
    'aimeos_account-favorite',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-favorite.description',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountFavorite.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountProfile',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-profile.title',
    'aimeos_account-profile',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-profile.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountProfile.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountReview',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-review.title',
    'aimeos_account-review',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-review.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountReview.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountSubscription',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-subscription.title',
    'aimeos_account-subscription',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-subscription.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountSubscription.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'AccountWatch',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-watch.title',
    'aimeos_account-watch',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_account-watch.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/AccountWatch.xml',
    $ctypeKey,
);

// ----------------------------------------------------------------------------

$ctypeKey = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'aimeos',
    'SupplierDetail',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_supplier-detail.title',
    'aimeos_supplier-detail',
    'aimeos',
    'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:aimeos_supplier-detail.description'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $ctypeKey,
    'after:subheader',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:aimeos/Configuration/FlexForms/SupplierDetail.xml',
    $ctypeKey,
);
