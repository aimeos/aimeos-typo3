<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$beUsersSiteFcn = function () {
    $table = 'mshop_locale_site';
    $tableConnection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Database\ConnectionPool::class
    )->getConnectionForTable($table);

    $queryBuilder = $tableConnection->createQueryBuilder();
    $list = [['', '']];

    if (empty($tableConnection->getSchemaManager()->listTableColumns($table)) !== true) {
        $result = $queryBuilder->select('siteid', 'label', 'nleft', 'nright')
            ->from($table)
            ->orderBy('nleft')
            ->execute();

        $parents = [];

        $fcn = function ($result, $parents, $right) use (&$fcn, &$list) {
            while ($row = $result->fetch()) {
                $list[] = [join(' > ', array_merge($parents, [$row['label']])), $row['siteid']];

                if ($row['nright'] - $row['nleft'] > 1) {
                    $fcn($result, array_merge($parents, [$row['label']]), $row['nright']);
                }

                if ($row['nright'] + 1 == $right) {
                    return;
                }
            }
        };

        while ($row = $result->fetch()) {
            $list[] = [$row['label'], $row['siteid']];

            if ($row['nright'] - $row['nleft'] > 1) {
                $fcn($result, array_merge($parents, [$row['label']]), $row['nright']);
            }
        }
    }

    return $list;
};

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', [
    'siteid' => [
        'label' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf:be_users_site.title',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => $beUsersSiteFcn(),
        ]
    ]
]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users', 'siteid', '', 'after:password');

