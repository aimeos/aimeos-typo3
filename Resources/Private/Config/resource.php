<?php

$publicPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath();

$dbConfig = [
	'host' => null,
	'port' => null,
	'socket' => null,
	'database' => null,
	'dbname' => null,
	'username' => null,
	'user' => null,
	'password' => null,
	'charset' => 'utf8',
	'collate' => 'utf8_unicode_ci'
];
foreach ($dbConfig as $key => &$value ) {
	if( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'][$key] ) ) {
		$value = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'][$key];
	} elseif( isset( $GLOBALS['TYPO3_CONF_VARS']['DB'][$key] ) ) {
		$value = $GLOBALS['TYPO3_CONF_VARS']['DB'][$key];
	}
}

return [
	'db' => [
		'adapter' => 'mysql',
		'host' => $dbConfig['host'],
		'port' => $dbConfig['port'],
		'socket' => $dbConfig['socket'],
		'database' => $dbConfig['dbname'] ?? $dbConfig['database'],
		'username' => $dbConfig['user'] ?? $dbConfig['username'],
		'password' => $dbConfig['password'],
		'stmt' => ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8'; SET SESSION sql_mode='ANSI'; SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED"],
		'defaultTableOptions' => [
			'charset' => $dbConfig['charset'],
			'collate' => $dbConfig['collate'],
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
		'baseurl' => '/typo3conf/ext/aimeos/Resources/Public/Images/Mimeicons',
		'basedir' => $publicPath . '/typo3conf/ext/aimeos/Resources/Public/Images/Mimeicons',
		'tempdir' => $publicPath . '/typo3temp',
	],
	'fs-admin' => [
		'adapter' => 'Standard',
		'basedir' => $publicPath . '/uploads/tx_aimeos/.secure/admin',
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
