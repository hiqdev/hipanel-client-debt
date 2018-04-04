<?php

return [
    'modules' => [
        'client' => [
            'controllerMap' => [
                'debt' => \hipanel\client\debt\controllers\DebtController::class,
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'client' => [
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
