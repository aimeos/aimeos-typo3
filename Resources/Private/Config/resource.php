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
);
