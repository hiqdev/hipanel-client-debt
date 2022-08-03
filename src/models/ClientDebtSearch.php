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
            'types', 'states', 'debt_ge', 'debt_le', 'debt_depth_ge', 'debt_depth_le', 'debt', 'login_email_like',
            'hide_internal', 'hide_vip','hide_prj',
            'total_debt_label',
            'inactive_period_ge',
            'legal_type',
            'email_verified',
            'plan_id',
            'any_balance_ge','any_balance_le',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'debt_label'        => Yii::t('hipanel.debt', 'Debt type'),
            'total_debt_label'  => Yii::t('hipanel.debt', 'Total debt type'),
            'debt_le'           => Yii::t('hipanel.debt', 'Debt up to'),
            'debt_ge'           => Yii::t('hipanel.debt', 'Debt from'),
            'debt_depth_le'     => Yii::t('hipanel.debt', 'Debt depth up to'),
            'debt_depth_ge'     => Yii::t('hipanel.debt', 'Debt depth from'),
            'any_balance_ge'    => Yii::t('hipanel.debt', 'Any balance from'),
            'any_balance_le'    => Yii::t('hipanel.debt', 'Any balance up to'),
            'total_balance_le'  => Yii::t('hipanel.debt', 'Total balance up to'),
            'total_balance_ge'  => Yii::t('hipanel.debt', 'Total balance from'),
            'balance_le'        => Yii::t('hipanel.debt', 'Balance to'),
            'balance_ge'        => Yii::t('hipanel.debt', 'Balance from'),
            'sold_services'     => Yii::t('hipanel.debt', 'Sold services'),
            'hide_vip'          => Yii::t('hipanel.debt', 'Hide VIP'),
            'hide_prj'          => Yii::t('hipanel.debt', 'Hide PRJ'),
            'template_id'       => Yii::t('hipanel.debt', 'Template'),
            'hide_internal'     => Yii::t('hipanel.debt', 'Hide internal'),
            'inactive_period_ge'=> Yii::t('hipanel.debt', 'Inactive period from'),
            'inactive_period_le'=> Yii::t('hipanel.debt', 'Inactive period up to'),
            'legal_type'        => Yii::t('hipanel.debt', 'Legal type'),
            'email_verified'    => Yii::t('hipanel.debt', 'Is email verified'),
            'sold_services_in'  => Yii::t('hipanel.debt', 'Sold services'),
            'plan_id'           => Yii::t('hipanel:finance', 'Tariff'),
        ]);
    }

}
