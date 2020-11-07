<?php

if( !defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$beSiteStmt = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Core\Database\ConnectionPool::class )
	->getQueryBuilderForTable( 'mshop_locale_site' )
    ->select( 'siteid', 'label', 'level' )
    ->from( 'mshop_locale_site' )
    ->orderBy( 'nleft' )
    ->execute();

$beSiteList = ['', ''];
while( $row = $beSiteStmt->fetch() ) {
    $beSiteList[] = [str_repeat( '  ', $row['level']) . $row['label'], $row['siteid']];
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'be_users', [
    'siteid' => [
        'label' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:be_users_site.title',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
			'items' => $beSiteList,
        ]
    ]
], true );

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'be_users', 'siteid', '', 'after:password' );