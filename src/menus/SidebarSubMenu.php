<?php

namespace hipanel\client\debt\menus;

use Yii;

/**
 * Class SidebarSubMenu.
 */
class SidebarSubMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        return [
            'clients' => [
                'items' => [
                    'debtors' => [
                        'label' => Yii::t('hipanel.debt', 'Debtors'),
                        'url' => ['/client/debt/index'],
                        'visible' => Yii::$app->user->can('manage'),
                    ],
                ],
            ],
        ];
    }
}
