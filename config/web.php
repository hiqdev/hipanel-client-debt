<?php

return [
    'aliases' => [
        '@debt' => '/client/debt',
    ],
    'modules' => [
        'client' => [
            'controllerMap' => [
                'debt' => \hipanel\client\debt\controllers\DebtController::class,
            ],
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel.debt' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/client/debt/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'clients' => [
                        'menu' => [
                            'merge' => [
                                'debts' => [
                                    'menu' => \hipanel\client\debt\menus\SidebarSubMenu::class,
                                    'where' => [
                                        'after' => ['documents', 'contacts'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
