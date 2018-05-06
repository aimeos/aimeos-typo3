<?php

if ( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_jsonapi'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_jsonapi', 'FILE:EXT:aimeos/Configuration/FlexForms/Jsonapi.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'jsonapi', 'Aimeos Shop - JSON REST API' );


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_locale-select'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_locale-select', 'FILE:EXT:aimeos/Configuration/FlexForms/LocaleSelect.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'locale-select', 'Aimeos Shop - Locale selector' );


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-attribute'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-attribute', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogAttribute.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-attribute', 'Aimeos Shop - Catalog attributes' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-count'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-count', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogCount.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-count', 'Aimeos Shop - Catalog count JSON' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-detail'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-detail', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogDetail.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-detail', 'Aimeos Shop - Catalog detail' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-filter'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-filter', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogFilter.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-filter', 'Aimeos Shop - Catalog filter' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-list'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-list', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogList.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-list', 'Aimeos Shop - Catalog list' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-search'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-search', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSearch.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-search', 'Aimeos Shop - Catalog search' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-session'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-session', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSession.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-session', 'Aimeos Shop - Catalog user related session' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-stage'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-stage', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogStage.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-stage', 'Aimeos Shop - Catalog stage area' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-stock'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-stock', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogStock.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-stock', 'Aimeos Shop - Catalog stock JSON' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-suggest'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-suggest', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogSuggest.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-suggest', 'Aimeos Shop - Catalog suggest JSON' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_catalog-tree'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_catalog-tree', 'FILE:EXT:aimeos/Configuration/FlexForms/CatalogTree.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'catalog-tree', 'Aimeos Shop - Catalog tree' );


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_basket-related'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_basket-related', 'FILE:EXT:aimeos/Configuration/FlexForms/BasketRelated.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'basket-related', 'Aimeos Shop - Basket related' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_basket-small'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_basket-small', 'FILE:EXT:aimeos/Configuration/FlexForms/BasketSmall.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'basket-small', 'Aimeos Shop - Basket small' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_basket-standard'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_basket-standard', 'FILE:EXT:aimeos/Configuration/FlexForms/BasketStandard.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'basket-standard', 'Aimeos Shop - Basket standard' );


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_checkout-confirm'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_checkout-confirm', 'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutConfirm.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'checkout-confirm', 'Aimeos Shop - Checkout confirm' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_checkout-standard'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_checkout-standard', 'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutStandard.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'checkout-standard', 'Aimeos Shop - Checkout standard' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_checkout-update'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_checkout-update', 'FILE:EXT:aimeos/Configuration/FlexForms/CheckoutUpdate.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'checkout-update', 'Aimeos Shop - Checkout payment update' );


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-download'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-download', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountDownload.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-download', 'Aimeos Shop - Account download' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-history'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-history', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountHistory.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-history', 'Aimeos Shop - Account history' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-favorite'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-favorite', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountFavorite.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-favorite', 'Aimeos Shop - Account favorite' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-profile'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-profile', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountProfile.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-profile', 'Aimeos Shop - Account profile' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-subscription'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-subscription', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountSubscription.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-subscription', 'Aimeos Shop - Account subscriptions' );

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['aimeos_account-watch'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( 'aimeos_account-watch', 'FILE:EXT:aimeos/Configuration/FlexForms/AccountWatch.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.aimeos', 'account-watch', 'Aimeos Shop - Account watch list' );


?>