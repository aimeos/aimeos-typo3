<?php

if( !defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$aimeosExtPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( 'aimeos' );

if( file_exists( $aimeosExtPath . '/Resources/Libraries/autoload.php' ) === true ) {
	require_once $aimeosExtPath . '/Resources/Libraries/autoload.php';
}


if( TYPO3_MODE === 'BE' )
{
	/**
	 * Register Aimeos icon
	 */

	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Core\Imaging\IconRegistry::class );
	$iconRegistry->registerIcon(
		'aimeos-shop',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:aimeos/Resources/Public/Icons/Extension.svg']
	);


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


	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher' );

	/**
	 * Execute the setup tasks automatically to create the required tables
	 */
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

	/**
	 * Prevent install tool from dropping Aimeos tables
	 */
	$signalSlotDispatcher->connect(
		'TYPO3\\CMS\\Install\\Service\\SqlExpectedSchemaService',
		'tablesDefinitionIsBeingBuilt', // @deprecated, use PSR Events in 11+
		'Aimeos\\Aimeos\\Setup',
		'schema'
	);
}

?>