<?php

if ( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Aimeos configuration' );

$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_aimeos_custom_wizicon'] = t3lib_extMgm::extPath( $_EXTKEY ) . 'Classes/Custom/Wizicon.php';


if ( TYPO3_MODE === 'BE' )
{
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',
		'tx_aimeos_admin',
		'', // position
		array(
			'Admin' => 'index,do',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/Admin.xml',
		)
	);
}


$pluginName = str_replace( '_', '', $_EXTKEY );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_locale-select'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_locale-select', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/LocaleSelect.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'locale-select', 'Aimeos - Locale selector' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-count'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-count', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogCount.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-count', 'Aimeos - Catalog count JSON' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-detail'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-detail', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogDetail.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-detail', 'Aimeos - Catalog detail' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-filter'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-filter', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogFilter.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-filter', 'Aimeos - Catalog filter' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-list'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-list', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogList.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-list', 'Aimeos - Catalog list' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-listsimple'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-listsimple', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogListSimple.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-listsimple', 'Aimeos - Catalog list JSON' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-session'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-session', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogSession.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-session', 'Aimeos - Catalog user related session' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-stage'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-stage', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogStage.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-stage', 'Aimeos - Catalog stage area' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-stock'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_catalog-stock', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogStock.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'catalog-stock', 'Aimeos - Catalog stock source' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-related'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_basket-related', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketRelated.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'basket-related', 'Aimeos - Basket related' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-small'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_basket-small', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketSmall.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'basket-small', 'Aimeos - Basket small' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-standard'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_basket-standard', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketStandard.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'basket-standard', 'Aimeos - Basket standard' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-confirm'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_checkout-confirm', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutConfirm.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'checkout-confirm', 'Aimeos - Checkout confirm' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-standard'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_checkout-standard', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutStandard.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'checkout-standard', 'Aimeos - Checkout standard' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-update'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_checkout-update', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutUpdate.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'checkout-update', 'Aimeos - Checkout payment update' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-history'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_account-history', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountHistory.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'account-history', 'Aimeos - Account history' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-favorite'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_account-favorite', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountFavorite.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'account-favorite', 'Aimeos - Account favorite' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-watch'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( $pluginName . '_account-watch', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountWatch.xml' );
Tx_Extbase_Utility_Extension::registerPlugin( $_EXTKEY, 'account-watch', 'Aimeos - Account watch list' );

?>
