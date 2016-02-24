<?php

return array(
	'db' => array(
		'adapter' => 'mysql',
		'host' => $GLOBALS['TYPO3_CONF_VARS']['DB']['host'],
		'port' => $GLOBALS['TYPO3_CONF_VARS']['DB']['port'],
		'socket' => $GLOBALS['TYPO3_CONF_VARS']['DB']['socket'],
		'database' => $GLOBALS['TYPO3_CONF_VARS']['DB']['database'],
		'username' => $GLOBALS['TYPO3_CONF_VARS']['DB']['username'],
		'password' => $GLOBALS['TYPO3_CONF_VARS']['DB']['password'],
		'stmt' => array(
			"SET NAMES 'utf8'",
			"SET SESSION sql_mode='ANSI'",
		),
	),
	'fs' => array(
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'uploads/tx_aimeos',
		'tempdir' => PATH_site . 'typo3temp',
	),
	'fs-admin' => array(
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'typo3temp',
		'tempdir' => PATH_site . 'typo3temp',
	),
	'fs-secure' => array(
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'uploads/tx_aimeos/.secure',
		'tempdir' => PATH_site . 'typo3temp',
	),
);
