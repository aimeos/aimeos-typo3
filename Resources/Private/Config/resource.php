<?php

$typo3_db_config_parts = explode( ':', TYPO3_db_host );

return array(
	'db' => array(
		'adapter' => 'mysql',
		'host' => $typo3_db_config_parts[0],
		'port' => ( isset( $typo3_db_config_parts[1] ) ? $typo3_db_config_parts[1] : '' ),
		'database' => TYPO3_db,
		'username' => TYPO3_db_username,
		'password' => TYPO3_db_password,
		'stmt' => array(
			"SET NAMES 'utf8'",
			"SET SESSION sql_mode='ANSI'",
		),
	),
);
