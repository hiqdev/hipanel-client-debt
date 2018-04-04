<?php

namespace hipanel\client\debt\models;

use hipanel\helpers\StringHelper;
use hipanel\models\Ref;
use hipanel\modules\client\forms\EmployeeForm;
use hipanel\modules\client\models\query\ClientQuery;
use hipanel\modules\domain\models\Domain;
use hipanel\modules\finance\models\Purse;
use hipanel\modules\server\models\Server;
use hipanel\modules\ticket\models\Thread;
use hipanel\validators\DomainValidator;
use Yii;

/**
 * Class ClientDebt model
 */
class ClientDebt extends \hipanel\modules\client\models\Client
{
    const SOLD_DEDICATED = 'dedicated';
    const SOLD_CDN = 'cdn';
    const SOLD_VIRTUAL = 'virtual';
    const SOLD_DEDICATED_CDN = 'dedicated_cdn';
    const SOLD_DEDICATED_VIRTUAL = 'dedicated_virtual';
    const SOLD_CDN_VIRTUAL = 'cdn_virtual';
    const SOLD_ALL = 'all';
    const SOLD_NOTHING = 'nothing';

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['full_balance', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt'], 'number'],
            [['full_balance', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt'], 'number'],
            [['financial_month', 'debt_depth', 'sold_services'], 'safe'],
            [['last_deposit_time'], 'date'],

        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_depth' => Yii::t('hipanel:client', 'Debt depth'),
            'debt_lt' => Yii::t('hipanel:client', 'Debt to'),
            'debt_gt' => Yii::t('hipanel:client', 'Debt from'),
            'debt_depth_lt' => Yii::t('hipanel:client', 'Debt depth to'),
            'debt_depth_gt' => Yii::t('hipanel:client', 'Debt depth from'),
            'sold_services' => Yii::t('hipanel:client', 'Sold services'),
        ]);
    }

}
