<?php

use hipanel\widgets\DateTimePicker;
use hiqdev\combo\StaticCombo;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var array $sold_services
 * @var \yii\web\View $this
 */
?>

<? include Yii::getAlias('@hipanel/modules/client/views/client/_search.php') ?>

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
    <?= $search->field('debt_gt') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_lt') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_gt') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('debt_depth_lt') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_vip')->checkbox() ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_prj')->checkbox() ?>
</div>
