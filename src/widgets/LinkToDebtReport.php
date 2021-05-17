<?php
declare(strict_types=1);

namespace hipanel\client\debt\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

class LinkToDebtReport extends Widget
{
    private ?string $linkToDebtReport;

    public function init(): void
    {
        $this->linkToDebtReport = Yii::$app->params['module.client-debt.debt-report.link'] ?? null;
        $this->view->registerCss(<<<CSS
            .bs-clipboard {
                position: relative;
                float: right;
            }

            .bs-clipboard  + .highlight {
                margin-top: 0;
            }

            .btn-clipboard {
                position: absolute;
                top: 0;
                right: 0;
                z-index: 10;
                display: block;
                padding: 4px 8px;
                font-size: 12px;
                color: #818a91;
                cursor: pointer;
                background-color: transparent;
                border: 0;
                border-top-right-radius: 4px;
                border-bottom-left-radius: 4px;
            }
            .btn-clipboard:hover {
                color: #fff;
                background-color: #46587a;
            }

            @media (min-width: 720px) {
                .bs-clipboard {
                    display: block;
                }
            }
CSS
        );
        $message = Yii::t('hipanel.debt', 'Token has been copied to the clipboard');
        $this->view->registerJs(<<<"JS"
            (() => {
                const btn = document.querySelector("#{$this->getId()} button.btn-clipboard");
                btn.addEventListener('click', function() {
                    const token = document.querySelector("#{$this->getId()} textarea");
                    token.focus();
                    token.select();
                    try {
                        const successful = document.execCommand('copy');
                        if (successful && typeof hipanel === 'object') {
                            hipanel.notify.success("$message");
                        }
                    } catch (err) {
                        console.log('Oops, unable to copy');
                    }
                });
            })();
JS
        );
        $this->view->on(View::EVENT_END_BODY, function () {
            Modal::begin([
                'id' => $this->getId(),
                'header' => Html::tag('h4', Yii::t('hipanel.debt', 'Debtor report'), ['class' => 'modal-title']),
                'toggleButton' => false,
            ]);

            echo Html::tag('p', Yii::t('hipanel.debt', 'Copy the token to generate a table in Google Sheets'));
            echo Html::tag('div', Html::button(Yii::t('hipanel.debt', 'Copy'), ['class' => 'btn-clipboard']), ['class' => 'bs-clipboard']);
            echo Html::textarea('token', Yii::$app->user->identity->getAccessToken(), [
                'rows' => 5,
                'class' => 'form-control highlight',
                'readonly' => true,
                'style' => 'margin-bottom: 2em; border-radius: 3px;',
            ]);

            echo Html::a(Yii::t('hipanel.debt', 'Go to report'), $this->linkToDebtReport, [
                'class' => 'btn btn-success btn-block',
                'target' => '_blank',
            ]);

            Modal::end();
        });
    }

    public function run(): string
    {
        if ($this->linkToDebtReport === null) {
            return "";
        }

        return Html::a(Yii::t('hipanel.debt', 'Debtor report'), ['#'], [
            'class' => 'btn btn-sm btn-success',
            'data' => [
                'toggle' => 'modal',
                'target' => '#' . $this->getId(),
            ],
        ]);
    }
}
