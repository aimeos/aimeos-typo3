<?php

$publicPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath();
$assetPath = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:aimeos/Resources/Public'));

$defaultConnection = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'] ?? [];
$driver = $defaultConnection['driver'] ?? null;

switch($driver) {
	case 'mysqli':
	case 'pdo_mysql': $adapter = 'mysql'; break;
	case 'pdo_oci': $adapter = 'oracle'; break;
	case 'pdo_pgsql': $adapter = 'pgsql'; break;
	case 'pdo_sqlite': $adapter = 'sqlite'; break;
	case 'pdo_sqlsrv': $adapter = 'sqlsrv'; break;
	default: $adapter = $driver;
}

switch($adapter) {
	case 'mysql': $stmt = ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8'; SET SESSION sql_mode='ANSI'; SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED"]; break;
	default: $stmt = [];
}

return [
	'db' => [
		'adapter' => $adapter,
		'host' => $defaultConnection['host'] ?? null,
		'port' => $defaultConnection['port'] ?? null,
		'socket' => $defaultConnection['socket'] ?? null,
		'database' => $defaultConnection['dbname'] ?? null,
		'username' => $defaultConnection['user'] ?? null,
		'password' => $defaultConnection['password'] ?? null,
		'stmt' => $stmt,
		'defaultTableOptions' => [
			'charset' => $defaultConnection['tableoptions']['charset'] ?? 'utf8',
			'collate' => $defaultConnection['tableoptions']['collate'] ?? 'utf8_unicode_ci',
		],
		'limit' => 3,
	],
	'fs' => [
		'adapter' => 'Standard',
		'baseurl' => '/uploads/tx_aimeos',
		'basedir' => $publicPath . '/uploads/tx_aimeos',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-media' => [
		'adapter' => 'Standard',
		'baseurl' => '/uploads/tx_aimeos',
		'basedir' => $publicPath . '/uploads/tx_aimeos',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-mimeicon' => [
		'adapter' => 'Standard',
		'baseurl' => $assetPath . '/Images/Mimeicons',
		'basedir' => $assetPath . '/Images/Mimeicons',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-theme' => [
		'adapter' => 'Standard',
		'baseurl' => $assetPath . '/Themes',
		'basedir' => $assetPath . '/Themes',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-admin' => [
		'adapter' => 'Standard',
		'basedir' => $publicPath . '/uploads/tx_aimeos/.secure/admin',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-export' => [
		'adapter' => 'Standard',
		'basedir' => $publicPath . '/uploads/tx_aimeos/.secure/export',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-import' => [
		'adapter' => 'Standard',
		'basedir' => $publicPath . '/uploads/tx_aimeos/.secure/import',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-secure' => [
		'adapter' => 'Standard',
		'basedir' => $publicPath . '/uploads/tx_aimeos/.secure',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'mq' => [
		'adapter' => 'Standard',
		'db' => 'db',
	],
];
