<?php

if (!defined( 'TYPO3_MODE' ) ) {
    die( 'Access denied.' );
}


$beUsersSiteFcn = function() {

    $list = [['', '']];

    try
    {
        $config = \Aimeos\Aimeos\Base::config();
        $context = \Aimeos\Aimeos\Base::context( $config );

        $result = $context->db( 'db-locale' )->create( 'SELECT * FROM "mshop_locale_site" ORDER BY "nleft"' )->execute();
        $parents = [];

        $fcn = function( $result, $parents, $right ) use ( &$fcn, &$list ) {

            while ( $row = $result->fetch() ) {
                $list[] = [join( ' > ', array_merge( $parents, [$row['label']] ) ), $row['siteid']];

                if ($row['nright'] - $row['nleft'] > 1 ) {
                    $fcn( $result, array_merge( $parents, [$row['label']] ), $row['nright'] );
                }

                if ($row['nright'] + 1 == $right ) {
                    return;
                }
            }
        };

        while ( $row = $result->fetch() )
        {
            $list[] = [$row['label'], $row['siteid']];

            if ($row['nright'] - $row['nleft'] > 1 ) {
                $fcn( $result, array_merge( $parents, [$row['label']] ), $row['nright'] );
            }
        }
    }
    catch( \Exception $e )
    {
        $log = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Core\Log\LogManager::class );
        $log->getLogger( __CLASS__ )->warning( 'Unable to retrieve Aimeos sites: ' . $e->getMessage() );
    }

    return $list;
};


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'be_users', [
    'siteid' => [
        'label' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:beusers_site.title',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => $beUsersSiteFcn(),
        ]
    ]
] );

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes( 'be_users', 'siteid', '', 'after:password' );

