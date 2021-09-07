<?php

use hipanel\modules\finance\helpers\CurrencyFilter;
use hipanel\client\debt\models\ClientDebt;
use hipanel\widgets\DateTimePicker;
use hiqdev\combo\StaticCombo;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var array $sold_services
 * @var \yii\web\View $this
 */
?>

<?php include Yii::getAlias('@hipanel/modules/client/views/client/_search.php') ?>

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
    <?= $search->field('sold_services')->widget(StaticCombo::class, [
        'data'      => $sold_services,
        'hasId'     => true,
        'multiple'  => true,
    ]) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_gt')->input('number', ['placeholder' => $search->model->getAttributeLabel('debt_depth_gt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_lt')->input('number', ['placeholder' => $search->model->getAttributeLabel('debt_depth_lt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, [
        'data' => CurrencyFilter::addSymbolAndFilter($this->context->getCurrencyTypes()),
        'hasId'     => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_gt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_gt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_lt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('debt_lt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_debt_label')->widget(StaticCombo::class, [
        'data' => $debt_label,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_balance_gt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('total_balance_gt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('total_balance_lt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('total_balance_lt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('legal_type')->widget(StaticCombo::class, [
        'data' => ClientDebt::getLegalTypeLabels(),
        'hasId' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('balance_gt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('balance_gt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('balance_lt')->input('number', ['step' => 0.01, 'placeholder' => $search->model->getAttributeLabel('balance_lt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inactive_period_gt')->input('number', ['placeholder' => $search->model->getAttributeLabel('inactive_period_gt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('inactive_period_lt')->input('number', ['placeholder' => $search->model->getAttributeLabel('inactive_period_lt')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_vip')->checkbox(['placeholder' => $search->model->getAttributeLabel('hide_vip')]) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_prj')->checkbox(['placeholder' => $search->model->getAttributeLabel('hide_prj')]) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('email_verified')->checkbox(['placeholder' => $search->model->getAttributeLabel('email_verified')]) ?>
</div>


