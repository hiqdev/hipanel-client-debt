<?php

/*
 * Debt Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-client-debt
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */


use hipanel\client\debt\models\ClientDebtSearch;
use hipanel\client\debt\widgets\LinkToDebtReport;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\client\grid\ClientGridLegend;
use hipanel\client\debt\grid\DebtGridView;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use hipanel\widgets\SummaryWidget;
use hiqdev\higrid\representations\RepresentationCollection;
use yii\bootstrap\Dropdown;
use hiqdev\assets\flagiconcss\FlagIconCssAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use hipanel\helpers\Url;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 * @var RepresentationCollection $representationCollection
 * @var float[] $local_sums
 * @var float[] $total_sums
 * @var ClientDebtSearch $model
 * @var View $this
 */

FlagIconCssAsset::register($this);

$this->title = Yii::t('hipanel.debt', 'Debtors');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?= $page->setSearchFormData(compact(['types', 'states', 'uiModel', 'sold_services', 'debt_label'])) ?>

    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:client', 'Create client'), ['@client/create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= LinkToDebtReport::widget() ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new ClientGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'login',
                'name',
                'seller',
                'type',
                'total_balance',
                'debt_depth',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>


    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('client.block')) : ?>
            <?php
            $dropDownItems = [
                [
                    'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable block'),
                    'linkOptions' => ['data-toggle' => 'modal'],
                    'url' => '#bulk-enable-block-modal',
                ],
                [
                    'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable block'),
                    'url' => '#bulk-disable-block-modal',
                    'linkOptions' => ['data-toggle' => 'modal'],
                ],
                [
                    'label' => '<i class="fa fa-envelope"></i> ' . Yii::t('hipanel.debt', 'Payment notification'),
                    'url' => '#bulk-create-ticket-notification-modal',
                    'linkOptions' => ['data-toggle' => 'modal'],
                ]
            ];
            $ajaxModals = [
                [
                    'id' => 'bulk-enable-block-modal',
                    'scenario' => Url::to('@client/bulk-enable-block-modal'),
                    'bulkPage' => true,
                    'usePost' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:client', 'Block clients'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-warning'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ],
                [
                    'id' => 'bulk-disable-block-modal',
                    'scenario' => Url::to('@client/bulk-disable-block-modal'),
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:client', 'Unblock clients'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-warning'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ],
                [
                    'id' => 'bulk-create-ticket-notification-modal',
                    'scenario' => Url::to('@debt/bulk-create-ticket-notification-modal'),
                    'bulkPage' => true,
                    'usePost' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel.debt', 'Payment notification'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-warning'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ],
            ];
            if (Yii::$app->user->can('client.delete')) {
                array_push($dropDownItems, [
                    'label' => '<i class="fa fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                    'url' => '#bulk-delete-modal',
                    'linkOptions' => ['data-toggle' => 'modal']
                ]);
                array_push($ajaxModals, [
                    'id' => 'bulk-delete-modal',
                    'scenario' => Url::to('@client/bulk-delete-modal'),
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title label-danger']),
                    'headerOptions' => ['class' => 'label-danger'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ]);
            }
            ?>
            <div class="dropdown" style="display: inline-block">
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('hipanel', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => $dropDownItems,
                ]) ?>
                <div class="text-left">
                    <?php foreach ($ajaxModals as $ajaxModal) : ?>
                        <?= AjaxModal::widget($ajaxModal) ?>
                    <?php endforeach ?>
               </div>
            </div>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= DebtGridView::widget([
                'boxed' => false,
                'rowOptions' => function ($model) {
                    return  GridLegend::create(new ClientGridLegend($model))->gridRowOptions();
                },
                'dataProvider' => $dataProvider,
                'filterModel'  => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                'summaryRenderer' => static function (DebtGridView $grid, Closure $defaultSummaryCb) use ($local_sums, $total_sums): string {
                    return $defaultSummaryCb() . SummaryWidget::widget([
                        'local_sums' => $local_sums,
                        'total_sums' => $total_sums,
                    ]);
                },
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
