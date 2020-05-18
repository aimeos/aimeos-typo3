<?php

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
		'limit' => 2,
	],
	'fs' => [
		'adapter' => 'Standard',
		'baseurl' => '/uploads/tx_aimeos',
		'basedir' => PATH_site . 'uploads/tx_aimeos',
		'tempdir' => PATH_site . 'typo3temp',
	],
	'fs-admin' => [
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'typo3temp/.aimeos',
		'tempdir' => PATH_site . 'typo3temp',
	],
	'fs-import' => [
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'fileadmin/.aimeos',
		'tempdir' => PATH_site . 'typo3temp',
	],
	'fs-secure' => [
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'uploads/tx_aimeos/.secure',
		'tempdir' => PATH_site . 'typo3temp',
	],
	'mq' => [
		'adapter' => 'Standard',
		'db' => 'db',
	],
];
