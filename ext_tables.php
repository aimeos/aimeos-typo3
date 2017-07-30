<?php

if ( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$localautoloader = __DIR__ . '/Resources/Libraries/autoload.php';

if( file_exists( $localautoloader ) === true ) {
	require_once $localautoloader;
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Aimeos Shop configuration' );


if ( TYPO3_MODE === 'BE' )
{
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms']['db_new_content_el']['wizardItemsHook'][] = 'Aimeos\\Aimeos\\Custom\\WizardItem';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['Aimeos\\Aimeos\\Custom\\Wizicon'] =
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( $_EXTKEY ) . 'Classes/Custom/Wizicon.php';

    $_moduleConfiguration = array(
        'access' => 'user,group',
        'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/admin.xlf',
    );
    if ( ! (bool)\Aimeos\Aimeos\Base::getExtConfig('showPageTree', true)) {
        $_moduleConfiguration['navigationComponentId'] = null;
        $_moduleConfiguration['inheritNavigationComponentFromMainModule'] = false;
    }

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Aimeos.' . $_EXTKEY,
		'web',
		'tx_aimeos_admin',
		'', // position
		array(
			'Admin' => 'index',
			'Jqadm' => 'search,copy,create,delete,export,get,import,save,file',
			'Extadm' => 'index,do,file',
			'Jsonadm' => 'index',
		),
		$_moduleConfiguration
	);
}


$pluginName = str_replace( '_', '', $_EXTKEY );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_jsonapi'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_jsonapi', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Jsonapi.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'jsonapi', 'Aimeos Shop - JSON REST API' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_locale-select'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_locale-select', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/LocaleSelect.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'locale-select', 'Aimeos Shop - Locale selector' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-count'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-count', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogCount.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-count', 'Aimeos Shop - Catalog count JSON' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-detail'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-detail', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogDetail.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-detail', 'Aimeos Shop - Catalog detail' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-filter'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-filter', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogFilter.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-filter', 'Aimeos Shop - Catalog filter' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-list'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-list', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogList.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-list', 'Aimeos Shop - Catalog list' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-suggest'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-suggest', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogSuggest.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-suggest', 'Aimeos Shop - Catalog suggest JSON' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-session'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-session', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogSession.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-session', 'Aimeos Shop - Catalog user related session' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-stage'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-stage', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogStage.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-stage', 'Aimeos Shop - Catalog stage area' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_catalog-stock'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_catalog-stock', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CatalogStock.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'catalog-stock', 'Aimeos Shop - Catalog stock JSON' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-related'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_basket-related', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketRelated.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'basket-related', 'Aimeos Shop - Basket related' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-small'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_basket-small', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketSmall.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'basket-small', 'Aimeos Shop - Basket small' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_basket-standard'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_basket-standard', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/BasketStandard.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'basket-standard', 'Aimeos Shop - Basket standard' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-confirm'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_checkout-confirm', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutConfirm.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'checkout-confirm', 'Aimeos Shop - Checkout confirm' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-standard'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_checkout-standard', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutStandard.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'checkout-standard', 'Aimeos Shop - Checkout standard' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_checkout-update'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_checkout-update', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/CheckoutUpdate.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'checkout-update', 'Aimeos Shop - Checkout payment update' );


$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-download'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_account-download', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountDownload.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'account-download', 'Aimeos Shop - Account download' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-history'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_account-history', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountHistory.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'account-history', 'Aimeos Shop - Account history' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-favorite'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_account-favorite', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountFavorite.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'account-favorite', 'Aimeos Shop - Account favorite' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-profile'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_account-profile', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountProfile.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'account-profile', 'Aimeos Shop - Account profile' );

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName . '_account-watch'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginName . '_account-watch', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/AccountWatch.xml' );
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin( 'Aimeos.' . $_EXTKEY, 'account-watch', 'Aimeos Shop - Account watch list' );

?>
