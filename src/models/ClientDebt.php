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
use hipanel\models\Ref;
use Yii;

/**
 * Class ClientDebt model
 */
class ClientDebt extends Client
{
    const TARIFF_NOT_SERVICE = [
        'client',
        'template',
        'referral',
        'calculator'
    ];
    const SOLD_DEDICATED = 'dedicated';
    const SOLD_CDN = 'cdn';
    const SOLD_VIRTUAL = 'virtual';
    const SOLD_DEDICATED_CDN = 'dedicated_cdn';
    const SOLD_DEDICATED_VIRTUAL = 'dedicated_virtual';
    const SOLD_CDN_VIRTUAL = 'cdn_virtual';
    const SOLD_ALL = 'all';
    const SOLD_NOTHING = 'nothing';

    const LEGAL_TYPE_COMPANY = 'company';
    const LEGAL_TYPE_PERSONAL = 'personal';

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
            [['total_balance', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt', 'debt', 'payment_ticket_id', 'template_id', 'balance'], 'number'],
            [['inactive_period'], 'integer'],
            [['financial_month', 'debt_depth', 'sold_services'], 'safe'],
            [['last_deposit_time'], 'date'],
            [['hide_vip', 'hide_prj', 'hide_internal'], 'boolean'],
            [['positive_balance', 'negative_balance'], 'number'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_depth'        => Yii::t('hipanel.debt', 'Debt depth'),
        ]);
    }

    public static function getSoldServices()
    {
        $services = Ref::getList('type,tariff');
        foreach (self::TARIFF_NOT_SERVICE as $type) {
            unset($services[$type]);
        }

        return array_merge([
            'all' => Yii::t('hipanel.debt', 'All'),
            'any' => Yii::t('hipanel.debt', 'Any'),
        ], $services, [
            'nothing' => Yii::t('hipanel.debt', 'Nothing'),
        ]);
    }

    public static function getLegalTypeLabels(): array
    {
        return [
            self::LEGAL_TYPE_COMPANY => Yii::t('hipanel.debt', 'Company'),
            self::LEGAL_TYPE_PERSONAL => Yii::t('hipanel.debt', 'Personal'),
        ];
    }

    public function getPayment_ticket()
    {
        if (!Yii::getAlias('@ticket', false)) {
            return null;
        }
        return $this->hasOne(Thread::class, ['id' => 'payment_ticket_id']);
    }
}
