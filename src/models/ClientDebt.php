<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-client-debt
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */


namespace hipanel\client\debt\models;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\client\models\Client;
use hipanel\modules\ticket\models\Thread;
use Yii;

/**
 * Class ClientDebt model
 */
class ClientDebt extends Client
{
    const SOLD_DEDICATED = 'dedicated';
    const SOLD_CDN = 'cdn';
    const SOLD_VIRTUAL = 'virtual';
    const SOLD_DEDICATED_CDN = 'dedicated_cdn';
    const SOLD_DEDICATED_VIRTUAL = 'dedicated_virtual';
    const SOLD_CDN_VIRTUAL = 'cdn_virtual';
    const SOLD_ALL = 'all';
    const SOLD_NOTHING = 'nothing';

    public static function tableName()
    {
        return 'clientdebt';
    }

     public function attributes()
     {
         $attributes = \yii\base\Model::attributes();
         foreach (self::rules() as $rule) {
             if (is_string(reset($rule))) {
                 continue;
             }
             foreach (reset($rule) as $attribute) {
                 if (substr_compare($attribute, '!', 0, 1) === 0) {
                     $attribute = mb_substr($attribute, 1);
                 }
                 $attributes[$attribute] = $attribute;
             }
         }
         return array_values($attributes);
     }

    public function rules()
    {
        return ArrayHelper::merge(Client::rules(), [
            [['full_balance', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt', 'debt', 'payment_ticket_id', 'template_id'], 'number'],
            [['financial_month', 'debt_depth', 'sold_services'], 'safe'],
            [['last_deposit_time'], 'date'],
            [['hide_vip', 'hide_prj'], 'boolean'],
            [['positive_balance', 'negative_balance'], 'number'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_depth'        => Yii::t('hipanel.debt', 'Debt depth'),
            'debt_lt'           => Yii::t('hipanel.debt', 'Debt to'),
            'debt_gt'           => Yii::t('hipanel.debt', 'Debt from'),
            'debt_depth_lt'     => Yii::t('hipanel.debt', 'Debt depth to'),
            'debt_depth_gt'     => Yii::t('hipanel.debt', 'Debt depth from'),
            'sold_services'     => Yii::t('hipanel.debt', 'Sold services'),
            'hide_vip'          => Yii::t('hipanel.debt', 'Hide VIP'),
            'hide_prj'          => Yii::t('hipanel.debt', 'Hide PRJ'),
            'template_id'       => Yii::t('hipanel.debt', 'Template'),
        ]);
    }

    public static function getSoldServices()
    {
        $sold_services = [
            self::SOLD_DEDICATED => Yii::t('hipanel.debt', 'Dedicated'),
            self::SOLD_CDN => Yii::t('hipanel.debt', 'CDN'),
            self::SOLD_VIRTUAL => Yii::t('hipanel.debt', 'Virtual'),
            self::SOLD_DEDICATED_CDN => Yii::t('hipanel.debt', 'Dedicated') . "+" . Yii::t('hipanel.debt', 'CDN'),
            self::SOLD_DEDICATED_VIRTUAL => Yii::t('hipanel.debt', 'Dedicated') . "+" . Yii::t('hipanel.debt', 'Virtual'),
            self::SOLD_CDN_VIRTUAL => Yii::t('hipanel.debt', 'CDN') . "+" . Yii::t('hipanel.debt', 'Virtual'),
            self::SOLD_ALL => Yii::t('hipanel.debt', 'Dedicated') . "+" . Yii::t('hipanel.debt', 'CDN') . "+" . Yii::t('hipanel.debt', 'Virtual'),
            self::SOLD_NOTHING => Yii::t('hipanel.debt', 'Nothing'),
        ];

        return $sold_services;
    }

    public function getPayment_ticket()
    {
        if (!Yii::getAlias('@ticket', false)) {
            return null;
        }
        return $this->hasOne(Thread::class, ['id' => 'payment_ticket_id']);
    }
}
