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
            'legal_type',
            'email_verified',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_label'        => Yii::t('hipanel.debt', 'Debt type'),
            'total_debt_label'  => Yii::t('hipanel.debt', 'Debt type'),
            'debt_lt'           => Yii::t('hipanel.debt', 'Debt up to'),
            'debt_gt'           => Yii::t('hipanel.debt', 'Debt from'),
            'debt_depth_lt'     => Yii::t('hipanel.debt', 'Debt depth up to'),
            'debt_depth_gt'     => Yii::t('hipanel.debt', 'Debt depth from'),
            'total_balance_lt'  => Yii::t('hipanel.debt', 'Total balance up to'),
            'total_balance_gt'  => Yii::t('hipanel.debt', 'Total balance from'),
            'balance_lt'        => Yii::t('hipanel.debt', 'Balance to'),
            'balance_gt'        => Yii::t('hipanel.debt', 'Balance from'),
            'sold_services'     => Yii::t('hipanel.debt', 'Sold services'),
            'hide_vip'          => Yii::t('hipanel.debt', 'Hide VIP'),
            'hide_prj'          => Yii::t('hipanel.debt', 'Hide PRJ'),
            'template_id'       => Yii::t('hipanel.debt', 'Template'),
            'hide_internal'     => Yii::t('hipanel.debt', 'Hide internal'),
            'inactive_period_gt'=> Yii::t('hipanel.debt', 'Inactive period from'),
            'inactive_period_lt'=> Yii::t('hipanel.debt', 'Inactive period up to'),
            'legal_type'        => Yii::t('hipanel.debt', 'Legal type'),
            'email_verified'    => Yii::t('hipanel.debt', 'Is email verified'),
        ]);
    }

}
