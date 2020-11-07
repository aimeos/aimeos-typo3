<?php

if( !defined( 'TYPO3_MODE' ) ) {
	die ( 'Access denied.' );
}


$beUsersSiteFcn = function() {

	$sql = 'SELECT "siteid", "label", "nleft", "nright" FROM "mshop_locale_site" ORDER BY "nleft"';
	$dbm = \Aimeos\Aimeos\Base::getContext( \Aimeos\Aimeos\Base::getConfig() )->db();

	$conn = $dbm->acquire( 'db-locale' );
	$result = $conn->create( $sql )->execute();

	$parents = [];
	$list = [['', '']];

	$fcn = function( $result, $parents, $right ) use ( &$fcn, &$list ) {

		while( $row = $result->fetch() )
		{
			$list[] = [join( ' > ', array_merge( $parents, [$row['label']] ) ), $row['siteid']];

			if( $row['nright'] - $row['nleft'] > 1 ) {
				$fcn( $result, array_merge( $parents, [$row['label']] ), $row['nright'] );
			}

			if( $row['nright'] + 1 == $right ) {
				return;
			}
		}
	};

	while( $row = $result->fetch() )
	{
		$list[] = [$row['label'], $row['siteid']];

		if( $row['nright'] - $row['nleft'] > 1 ) {
			$fcn( $result, array_merge( $parents, [$row['label']] ), $row['nright'] );
		}
	}

	$dbm->release( $conn, 'db-locale' );

	return $list;
};


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'be_users', [
    'siteid' => [
        'label' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:be_users_site.title',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
			'items' => $beUsersSiteFcn(),
        ]
    ]
] );

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'be_users', 'siteid', '', 'after:password' );
