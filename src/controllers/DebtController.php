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
                    $event->sender->getDataProvider()->query->withPaymentTicket();
                },
                'data' => function ($action) {
                    return [
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
