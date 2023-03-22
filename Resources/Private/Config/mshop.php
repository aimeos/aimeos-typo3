<?php

$defaultConnection = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'] ?? [];
$driver = $defaultConnection['driver'] ?? null;

switch($driver) {
	case 'mysql':
	case 'mysqli':
	case 'pdo_mysql': $manager = 'MySQL'; break;
	case 'pgsql':
	case 'pdo_pgsql': $manager = 'PgSQL'; break;
	case 'sqlsrv':
	case 'pdo_sqlsrv': $manager = 'SQLSrv'; break;
	default: $manager = 'Standard';
}

return [
	'customer' => [
		'manager' => [
			'name' => 'Typo3',
		],
	],
	'index' => [
		'manager' => [
			'name' => $manager,
			'attribute' => [
				'name' => $manager,
			],
			'catalog' => [
				'name' => $manager,
			],
			'price' => [
				'name' => $manager,
			],
			'supplier' => [
				'name' => $manager,
			],
			'text' => [
				'name' => $manager,
			],
		],
	],
];
