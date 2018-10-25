<?php

return array(
	'db' => array(
		'adapter' => 'mysql',
		'host' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['host'] ),
		'port' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['port'] ),
		'socket' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['socket'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['socket'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] ),
		'database' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['database'] ),
		'username' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['username'] ),
		'password' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] : $GLOBALS['TYPO3_CONF_VARS']['DB']['password'] ),
		'stmt' => array("SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8'; SET SESSION sql_mode='ANSI'"),
		'defaultTableOptions' => array(
			'charset' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['charset'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['charset'] : 'utf8' ),
			'collate' => ( isset( $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] ) ? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] : 'utf8_bin' ),
		),
		'limit' => 2,
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
	'fs-import' => array(
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'uploads/tx_aimeos/.secure/import',
		'tempdir' => PATH_site . 'typo3temp',
	),
	'fs-secure' => array(
		'adapter' => 'Standard',
		'basedir' => PATH_site . 'uploads/tx_aimeos/.secure',
		'tempdir' => PATH_site . 'typo3temp',
	),
	'mq' => array(
		'adapter' => 'Standard',
		'db' => 'db',
	),
);
