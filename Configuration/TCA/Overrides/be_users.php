<?php

if( !defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'be_users', [
	'siteid' => [
		'label' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:be_users_site.title',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'userFunc' => 'Aimeos\\Aimeos\\Custom\\Sites->tca',
		]
	]
] );

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'be_users', 'siteid', '', 'after:password' );