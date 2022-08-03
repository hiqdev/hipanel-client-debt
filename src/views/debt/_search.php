<?php

use hipanel\modules\finance\helpers\CurrencyFilter;
use hipanel\client\debt\models\ClientDebt;
use hipanel\widgets\DateTimePicker;
use hiqdev\combo\StaticCombo;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use hipanel\modules\finance\models\Plan;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var array $sold_services
 * @var \yii\web\View $this
 */
?>

<?php include Yii::getAlias('@hipanel/modules/client/views/client/_search.php') ?>

<div class="col-md-4 col-sm-6 col-xs-12 checkbox">
    <?= $search->field('hide_vip')->checkbox(['placeholder' => $search->model->getAttributeLabel('hide_vip')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12 checkbox">
    <?= $search->field('hide_prj')->checkbox(['placeholder' => $search->model->getAttributeLabel('hide_prj')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12 checkbox">
    <?= $search->field('email_verified')->checkbox(['placeholder' => $search->model->getAttributeLabel('email_verified')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:client', 'Financial month'), ['class' => 'control-label']); ?>
        <?= DateTimePicker::widget([
            'model'         => $search->model,
            'attribute'     => 'financial_month',
            'clientOptions' => [
                'autoclose' => true,
                'todayBtn' => true,
                'startView' => 'year',
                'minView' => 3,
                'format'    => 'yyyy-mm-01',
            ],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('plan_id')->widget(PlanCombo::class, [
        'hasId' => true,
        'multiple' => false,
        'tariffType' => [
            Plan::TYPE_SERVER,
            Plan::TYPE_PCDN,
            Plan::TYPE_VCDN,
            Plan::TYPE_CERTIFICATE,
            Plan::TYPE_DOMAIN,
            Plan::TYPE_SWITCH,
            Plan::TYPE_AVDS,
            Plan::TYPE_OVDS,
            Plan::TYPE_SVDS,
            Plan::TYPE_HARDWARE,
            Plan::TYPE_ANYCASTCDN,
            Plan::TYPE_VPS,
            Plan::TYPE_SNAPSHOT,
            Plan::TYPE_VOLUME,
            Plan::TYPE_STORAGE,
            Plan::TYPE_PRIVATE_CLOUD_BACKUP,
            Plan::TYPE_PRIVATE_CLOUD,
        ],
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('sold_services_in')->widget(StaticCombo::class, [
        'data'      => $sold_services,
        'hasId'     => true,
        'multiple'  => true,
    ]) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_ge')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_depth_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_le')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_depth_le')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, [
        'data' => CurrencyFilter::addSymbolAndFilter($this->context->getCurrencyTypes()),
        'hasId'     => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_ge')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_le')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_le')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_debt_label')->widget(StaticCombo::class, [
        'data' => $debt_label,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('any_balance_ge')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('any_balance_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('any_balance_le')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('any_balance_le')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_balance_ge')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('total_balance_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_balance_le')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('total_balance_le')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('legal_type')->widget(StaticCombo::class, [
        'data' => ClientDebt::getLegalTypeLabels(),
        'hasId' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('balance_ge')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('balance_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('balance_le')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('balance_le')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inactive_period_ge')->input('number', ['placeholder' => $search->model->getAttributeLabel('inactive_period_ge')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inactive_period_le')->input('number', ['placeholder' => $search->model->getAttributeLabel('inactive_period_le')]) ?>
</div>



