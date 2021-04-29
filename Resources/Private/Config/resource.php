<?php

$publicPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath();

return [
	'db' => [
		'adapter' => 'mysql',
		'host' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['host'],
		'port' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['port'],
		'socket' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['socket'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['socket'],
		'database' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['database'],
		'username' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['username'],
		'password' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['password'],
		'stmt' => ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8'; SET SESSION sql_mode='ANSI'; SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED"],
		'defaultTableOptions' => [
			'charset' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['charset'] ?? 'utf8',
			'collate' => $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] ?? 'utf8_unicode_ci',
		],
		'limit' => 3,
	],
	'fs' => [
		'adapter' => 'Standard',
		'baseurl' => '/uploads/tx_aimeos',
		'basedir' => $publicPath . '/uploads/tx_aimeos',
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
