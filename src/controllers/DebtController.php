<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\client\controllers;

use hipanel\actions\IndexAction;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\client\models\Client;
use Yii;
use yii\base\Event;
use yii\filters\VerbFilter;

class DebtController extends \hipanel\base\CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => ['manage'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    $action = $event->sender;
                    $query = $action->getDataProvider()->query;
                    $representation = $action->controller->indexPageUiOptionsModel->representation;

                    if (in_array($representation, ['servers', 'payment'], true)) {
                        $query->addSelect(['purses'])->withPurses();
                    }

                    switch ($representation) {
                        case 'payment':
                            $query->withPaymentTicket()->addSelect(['full_balance', 'debts_period']);
                            break;
                        case 'servers':
                            $query->addSelect(['accounts_count', Yii::getAlias('@server', false) ? 'servers_count' : null]);
                            break;
                        case 'documents':
                            $query->addSelect(['documents']);
                            break;
                    }
                },
                'data' => function ($action) {
                    return [
                        'types' => $this->getRefs('type,client', 'hipanel:client'),
                        'states' => $this->getRefs('state,client', 'hipanel:client'),
                        'sold_services' => Client::getSoldServices(),
                    ];
                },
                'filterStorageMap' => [
                    'login_ilike' => 'client.client.login_ilike',
                    'state' => 'client.client.state',
                    'type' => 'client.client.type',
                    'seller' => 'client.client.seller',
                    'seller_id' => 'client.client.seller_id',
                    'name_ilike' => 'client.client.name_ilike',
                ],
            ],
        ]);
    }
}
