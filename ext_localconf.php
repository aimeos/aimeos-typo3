<?php

if ( ! defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$localautoloader = __DIR__ . '/Resources/Libraries/autoload.php';

if( file_exists( $localautoloader ) === true ) {
	require_once $localautoloader;
}


/**
 * Include Aimeos extension directory
 */

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['confDirs']['0_'.$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Resources/Private/Config/';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['extDirs']['0_'.$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Resources/Private/Extensions/';


/**
 * Aimeos plugins
 */

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'locale-select',
	array( 'Locale' => 'select' ),
	array( 'Locale' => 'select' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-attribute',
	array( 'Catalog' => 'attribute' ),
	array( 'Catalog' => 'attribute' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-count',
	array( 'Catalog' => 'count' ),
	array( 'Catalog' => 'count' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-detail',
	array( 'Catalog' => 'detail' ),
	array( 'Catalog' => 'detail' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-filter',
	array( 'Catalog' => 'filter' ),
	array( 'Catalog' => 'filter' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-list',
	array( 'Catalog' => 'list' ),
	array( 'Catalog' => 'list' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-search',
	array( 'Catalog' => 'search' ),
	array( 'Catalog' => 'search' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-session',
	array( 'Catalog' => 'session' ),
	array( 'Catalog' => 'session' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-stage',
	array( 'Catalog' => 'stage' ),
	array( 'Catalog' => 'stage' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-stock',
	array( 'Catalog' => 'stock' ),
	array( 'Catalog' => 'stock' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-suggest',
	array( 'Catalog' => 'suggest' ),
	array( 'Catalog' => 'suggest' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'catalog-tree',
	array( 'Catalog' => 'tree' ),
	array( 'Catalog' => 'tree' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'basket-related',
	array( 'Basket' => 'related' ),
	array( 'Basket' => 'related' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'basket-small',
	array( 'Basket' => 'small' ),
	array( 'Basket' => 'small' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'basket-standard',
	array( 'Basket' => 'index' ),
	array( 'Basket' => 'index' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'checkout-standard',
	array( 'Checkout' => 'index' ),
	array( 'Checkout' => 'index' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'checkout-confirm',
	array( 'Checkout' => 'confirm' ),
	array( 'Checkout' => 'confirm' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'checkout-update',
	array( 'Checkout' => 'update' ),
	array( 'Checkout' => 'update' )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-download',
	array( 'Account' => 'download' ),
	array( 'Account' => 'download' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-history',
	array( 'Account' => 'history' ),
	array( 'Account' => 'history' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-favorite',
	array( 'Account' => 'favorite' ),
	array( 'Account' => 'favorite' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-profile',
	array( 'Account' => 'profile' ),
	array( 'Account' => 'profile' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-subscription',
	array( 'Account' => 'subscription' ),
	array( 'Account' => 'subscription' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'account-watch',
	array( 'Account' => 'watch' ),
	array( 'Account' => 'watch' )
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Aimeos.' . $_EXTKEY,
	'jsonapi',
	array( 'Jsonapi' => 'index' ),
	array( 'Jsonapi' => 'index' )
);


/**
 * Aimeos scheduler tasks
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Typo6'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/scheduler.xlf:default.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/scheduler.xlf:default.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Typo6',
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aimeos\\Aimeos\\Scheduler\\Task\\Email6'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/scheduler.xlf:email.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/scheduler.xlf:email.description',
	'additionalFields' => 'Aimeos\\Aimeos\\Scheduler\\Provider\\Email6',
);


/**
 * Add RealURL configuration
 */

if( ( $aimeosExtConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['aimeos'] ) ) !== false
	&& isset( $aimeosExtConf['useRealUrlAutoConfig'] ) && $aimeosExtConf['useRealUrlAutoConfig'] != 0
) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration']['aimeos'] =
		'EXT:aimeos/Classes/Custom/Realurl.php:Aimeos\\Aimeos\\Custom\\Realurl->addAutoConfig';
}


/**
 * Add cache configuration
 */

if( !is_array( $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos'] ) ) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos'] = array();
}

if( !isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] ) ) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend';
}

if( !isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['options'] ) ) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['options'] = array( 'defaultLifetime' => 0 );
}

if( !isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] ) ) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['aimeos']['groups'] = array( 'pages' );
}



/**
 * Execute the setup tasks automatically to create the required tables
 */

if (TYPO3_MODE === 'BE') {
	$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	$signalSlotDispatcher->connect(
		'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
		'hasInstalledExtensions',
		'Aimeos\\Aimeos\\Setup',
		'executeOnSignal'
	);
}

?>