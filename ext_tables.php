<?php

defined('TYPO3') or die();


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('aimeos');

if (file_exists($aimeosExtPath . '/Resources/Libraries/autoload.php') === true) {
    require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}


/**
 * Register Aimeos icon
 */

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'aimeos-shop',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg']
);


/**
 * Register backend module
 */
/*
$_aimeosConfiguration = [
    'access' => 'user,group',
    'icon' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg',
    'labels' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf',
];

if ((bool) \Aimeos\Aimeos\Base::getExtConfig('showPageTree', false) == false) {
    $_aimeosConfiguration['navigationComponentId'] = null;
    $_aimeosConfiguration['inheritNavigationComponentFromMainModule'] = false;
}

$name = defined('TYPO3_version') && version_compare(constant('TYPO3_version'), '11.0.0', '<') ? 'Aimeos.' : '';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    $name . 'aimeos',
    'web',
    'tx_aimeos_admin',
    '', // position
    [
        'Aimeos\\Aimeos\\Controller\\AdminController' => 'index',
        'Aimeos\\Aimeos\\Controller\\JqadmController' => 'search,batch,copy,create,delete,export,get,import,save,file',
        'Aimeos\\Aimeos\\Controller\\JsonadmController' => 'index',
        'Aimeos\\Aimeos\\Controller\\GraphqlController' => 'index',
    ],
    $_aimeosConfiguration
);


$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');

$signalSlotDispatcher->connect(
    'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
    'hasInstalledExtensions', // @deprecated, use "afterExtensionInstall" in TYPO3 10+ and PSR Events in 11+
    'Aimeos\\Aimeos\\Setup',
    'signal'
);
$signalSlotDispatcher->connect(
    'TYPO3\CMS\Extensionmanager\Utility\InstallUtility',
    'afterExtensionInstall', // @deprecated, use PSR Events in 11+
    'Aimeos\\Aimeos\\Setup',
    'signal'
);

$signalSlotDispatcher->connect(
    'TYPO3\\CMS\\Install\\Service\\SqlExpectedSchemaService',
    'tablesDefinitionIsBeingBuilt', // @deprecated, use PSR Events in 11+
    'Aimeos\\Aimeos\\Setup',
    'schema'
);
*/

?>