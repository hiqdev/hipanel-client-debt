<?php
/**
 * ClientDebt module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-client-debt
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\client\debt\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class ClientDebtSearch extends ClientDebt
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'created_from', 'created_till',
            'types', 'states', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt', 'debt', 'login_email_like',
            'hide_internal', 'hide_vip','hide_prj',
            'total_debt_label',
            'inactive_period_gt',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_lt'           => Yii::t('hipanel.debt', 'Debt to'),
            'debt_gt'           => Yii::t('hipanel.debt', 'Debt from'),
            'debt_depth_lt'     => Yii::t('hipanel.debt', 'Debt depth to'),
            'debt_depth_gt'     => Yii::t('hipanel.debt', 'Debt depth from'),
            'sold_services'     => Yii::t('hipanel.debt', 'Sold services'),
            'hide_vip'          => Yii::t('hipanel.debt', 'Hide VIP'),
            'hide_prj'          => Yii::t('hipanel.debt', 'Hide PRJ'),
            'template_id'       => Yii::t('hipanel.debt', 'Template'),
            'hide_internal'     => Yii::t('hipanel.debt', 'Hide internal'),
            'inactive_period_gt'=> Yii::t('hipanel.debt', 'Inactive period'),
        ]);
    }

}
