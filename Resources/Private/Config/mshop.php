<?php

$defaultConnection = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'];
$driver = $defaultConnection['driver'];

/* setup class name for aimeos-core/src/MShop/Index/Manager/ */
switch($driver) {
	case 'mysql':
	case 'mysqli':
	case 'pdo_mysql': $mshopIndexManagerAdapter = 'MySQL'; break;
	case 'pgsql':
	case 'pdo_pgsql': $mshopIndexManagerAdapter = 'PgSQL'; break;
	case 'sqlsrv':
	case 'pdo_sqlsrv': $mshopIndexManagerAdapter = 'SQLSrv'; break;
	default: $mshopIndexManagerAdapter = 'Standard';
}

return [
	'customer' => [
		'manager' => [
			'name' => 'Typo3',
		],
	],
	'index' => [
		'manager' => [
			'name' => $mshopIndexManagerAdapter,
			'attribute' => [
				'name' => $mshopIndexManagerAdapter,
			],
			'catalog' => [
				'name' => $mshopIndexManagerAdapter,
			],
			'price' => [
				'name' => $mshopIndexManagerAdapter,
			],
			'supplier' => [
				'name' => $mshopIndexManagerAdapter,
			],
			'text' => [
				'name' => $mshopIndexManagerAdapter,
			],
		],
	],
];
