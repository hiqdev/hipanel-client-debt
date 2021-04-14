<?php
/**
 * Client debt module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\client\debt\grid;

use hipanel\modules\client\grid\ClientGridView;
use hipanel\helpers\Url;
use hipanel\modules\client\models\Client;
use yii\helpers\Html;
use Yii;

class DebtGridView extends ClientGridView
{
    public function columns(): array
    {
        return array_merge(parent::columns(), [
            'login_without_note' => [
                'attribute' => 'login',
                'filterAttribute' => 'login_ilike',
                'format' => 'html',
                'value' => fn(Client $client): string => Html::a($client->login, ['@client/view', 'id' => $client->id], ['class' => 'text-bold']),
            ],
            'payment_ticket' => [
                'format' => 'html',
                'label' => Yii::t('hipanel', 'Ticket'),
                'filter' => false,
                'value' => function ($model) {
                    if (!$model->payment_ticket_id) {
                        return '';
                    }

                    if ($model->balance >= 0 && $model->payment_ticket->state === 'opened') {
                        $class = 'text-red';
                    } elseif ($model->balance >= 0) {
                        $class = 'text-purple';
                    } elseif ($model->payment_ticket->state === 'closed') {
                        $class = 'text-red bold';
                    } elseif ($model->payment_ticket->status === 'wait_admin') {
                        $class = 'text-green';
                    } else {
                        $class = 'text-blue';
                    }

                    return Html::a($model->payment_ticket_id, Url::to(['@ticket/view', 'id' => $model->payment_ticket_id]), compact('class'));
                },
            ],
            'payment_status' => [
                'label' => Yii::t('hipanel.debt', 'Payment status'),
                'attribute' => 'payment_ticket',
                'filter' => false,
                'value' => function ($model) {
                    if (!$model->payment_ticket_id) {
                        return '';
                    }

                    if ($model->balance >= 0) {
                        return Yii::t('hipanel.debt', 'Already payed');
                    }

                    return Yii::t('hipanel.debt', 'Debt');
                },
            ],
            'ticket_status' => [
                'label' => Yii::t('hipanel.debt', 'Ticket state'),
                'attribute' => 'payment_ticket',
                'filter' => false,
                'value' => function ($model) {
                    if (!$model->payment_ticket_id) {
                        return '';
                    }

                    $state = $model->payment_ticket->state === 'opened' ? Yii::t('hipanel.debt', 'Opened') : Yii::t('hipanel.debt', 'Closed');
                    $status = $model->payment_ticket->status === 'wait_admin' ? Yii::t('hipanel.debt', 'Wait for staff') : Yii::t('hipanel.debt', 'Wait for client');

                    return "{$state} / {$status}";
                },
            ],
            'last_deposit' => [
                'label' => Yii::t('hipanel:client', 'Last deposit'),
                'attribute' => 'last_deposit_time',
                'format' => 'date',
                'filter' => false,
            ],
            'debt_depth' => [
                'filter' => false,
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->balance >= 0) {
                        return '';
                    }
                    if ($model->debt_depth === null || (int)$model->debt_depth > 1000) {
                        return Html::tag('span', Yii::t('hipanel:client', '&#8734;'), ['class' => 'text-red']);
                    }

                    return Html::tag('span', sprintf("%01.2f", "{$model->debt_depth}"), ['class' => 'text-blue']);
                },
            ],
            'sold_services' => [
                'format' => 'html',
                'filter' => false,
                'label' => Yii::t('hipanel.debt', 'Sold services'),
                'value' => function ($model) {
                    foreach (json_decode($model->sold_services, true) as $sold_service => $value) {
                        $sold_services[] = Html::tag('span', strtoupper(substr($sold_service, 0, 1)), ['class' => $value ? 'text-green text-bold' : 'text-red']);
                    }

                    return implode(' / ', $sold_services);
                },
            ],
        ]);
    }
}
