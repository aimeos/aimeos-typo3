<?php

if( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


if( file_exists( __DIR__ . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once __DIR__ . '/Resources/Libraries/autoload.php';
}


if( TYPO3_MODE === 'BE' )
{
	/**
	 * Register Aimeos icon
	 */

	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
	$iconRegistry->registerIcon(
		'aimeos-shop',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg']
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms']['db_new_content_el']['wizardItemsHook'][] = 'Aimeos\\Aimeos\\Custom\\WizardItem';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['Aimeos\\Aimeos\\Custom\\Wizicon'] =
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' ) . 'Classes/Custom/Wizicon.php';


	/**
	 * Register backend module
	 */

	$_aimeosConfiguration = array(
		'access' => 'user,group',
		'icon' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg',
		'labels' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf',
	);

	if( (bool) \Aimeos\Aimeos\Base::getExtConfig( 'showPageTree', false ) == false )
	{
		$_aimeosConfiguration['navigationComponentId'] = null;
		$_aimeosConfiguration['inheritNavigationComponentFromMainModule'] = false;
	}

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Aimeos.aimeos',
		'web',
		'tx_aimeos_admin',
		'', // position
		array(
			'Admin' => 'index',
			'Jqadm' => 'search,copy,create,delete,export,get,import,save,file',
			'Extadm' => 'index,do,file',
			'Jsonadm' => 'index',
		),
		$_aimeosConfiguration
	);


	/**
	 * Execute the setup tasks automatically to create the required tables
	 */
	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	$signalSlotDispatcher->connect(
		'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
		'hasInstalledExtensions', // @deprecated, use "afterExtensionInstall" in TYPO3 10
		'Aimeos\\Aimeos\\Setup',
		'signal'
	);
	$signalSlotDispatcher->connect(
		'TYPO3\\CMS\\Install\\Service\\SqlExpectedSchemaService',
		'tablesDefinitionIsBeingBuilt',
		'Aimeos\\Aimeos\\Setup',
		'schema'
	);
}


?>