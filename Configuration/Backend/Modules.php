<?php

return [
    'web_aimeos' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'extensionName' => 'aimeos',
        'iconIdentifier' => 'aimeos-shop',
        'path' => '/module/web/aimeos',
        'labels' => 'LLL:EXT:aimeos/Resources/Private/Language/admin.xlf',
        'inheritNavigationComponentFromMainModule' => \Aimeos\Aimeos\Base::getExtConfig('showPageTree', false) ? true : false,
        'controllerActions' => [
            \Aimeos\Aimeos\Controller\AdminController::class => [
                'index'
            ],
            \Aimeos\Aimeos\Controller\JqadmController::class => [
                'search',
                'batch',
                'copy',
                'create',
                'delete',
                'export',
                'get',
                'import',
                'save',
                'file'
            ],
            \Aimeos\Aimeos\Controller\JsonadmController::class => [
                'index'
            ],
            \Aimeos\Aimeos\Controller\GraphqlController::class => [
                'index'
            ],
        ],
    ],
];
