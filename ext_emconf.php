<?php

########################################################################
# Extension Manager/Repository config file for ext: "aimeos"
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF['aimeos'] = [
    'title' => 'Aimeos shop and e-commerce framework',
    'description' => 'Professional, full-featured and ultra-fast TYPO3 e-commerce extension for online shops, complex B2B applications and #gigacommerce. Turns TYPO3 into the best platform for content commerce and your e-commerce requirements (also available as TYPO3 distribution)',
    'category' => 'plugin',
    'version' => '24.4.0-dev',
    'module' => '',
    'state' => 'beta',
    'modify_tables' => '',
    'clearcacheonload' => 1,
    'author' => 'Aimeos',
    'author_email' => 'aimeos@aimeos.org',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.99.99',
            'typo3' => '12.4.0-12.99.99',
            'scheduler' => '12.4.0-12.99.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Aimeos\\Aimeos\\' => 'Classes',
        ],
    ],
];

?>
