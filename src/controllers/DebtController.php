<?php
/**
 * ClientDebt module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-client-debt
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\client\debt\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RenderAction;
use hipanel\filters\EasyAccessControl;
use hipanel\client\debt\models\ClientDebt;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\modules\ticket\models\Template;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use Yii;

class DebtController extends \hipanel\base\CrudController
{
    protected $_myViewPath;

    public function init()
    {
        parent::init();
        $this->_myViewPath = dirname(__DIR__) . '/views/' . $this->id;
    }

    public static function modelClassName()
    {
        return ClientDebt::class;
    }

    public function getViewPath()
    {
        return $this->_myViewPath;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => ['client.block'],
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
                    $event->sender->getDataProvider()->query->withPaymentTicket();
                },
                'data' => function (RenderAction $action, array $data): array {
                    $query = clone $action->parent->dataProvider->query;
                    $query->andWhere(['groupby' => 'total_balance']);
                    $local_sums = [];
                    foreach ($data['dataProvider']->getModels() as $model) {
                        $balance = (float)$model->balance;
                        $local_sums[$model->currency]['total'] += $model->balance;
                        if ($balance < 0) {
                            $local_sums[$model->currency]['negative'] -= $model->balance;
                        } else {
                            $local_sums[$model->currency]['positive'] += $model->balance;
                        }
                    }
                    $total_sums = [];
                    foreach ($query->all() as $model) {
                        $total_sums[$model->currency]['total'] = (float)$model->balance;
                        $total_sums[$model->currency]['negative'] = abs((float)$model->negative_balance);
                        $total_sums[$model->currency]['positive'] = (float)$model->positive_balance;
                    }
                    return [
                        'local_sums' => $local_sums,
                        'total_sums' => $total_sums,
                        'types' => $this->getRefs('type,client', 'hipanel:client'),
                        'states' => $this->getRefs('state,client', 'hipanel:client'),
                        'sold_services' => ClientDebt::getSoldServices(),
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
            'create-payment-ticket' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:client', 'Notification was created'),
                'on beforeSave' => function(Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $template_id = Yii::$app->request->post('template_id');
                    if (!empty($template_id)) {
                        foreach ($action->collection->models as $model) {
                            $model->setAttributes(array_filter([
                                'template_id' => $template_id,
                            ]));
                        }
                    }
                },
            ],
            'bulk-create-ticket-notification-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_bulkNotificationTickets',
                'data' => function($action, $data) {
                    $templates = ArrayHelper::map(Template::find()->all(), 'id', 'name');
                    return array_merge($data, [
                        'templates' => $templates,
                    ]);
                },
            ],
        ]);
    }
}
