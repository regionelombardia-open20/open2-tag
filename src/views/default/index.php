<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

/** @var \lispa\amos\dashboard\models\AmosUserDashboards $currentDashboard * */
/** @var \yii\web\View $this * */

use lispa\amos\core\icons\AmosIcons;
use lispa\amos\layout\assets\BaseAsset;
use lispa\amos\dashboard\AmosDashboard;
use lispa\amos\dashboard\assets\ModuleDashboardAsset;
use yii\helpers\Html;
use yii\web\JsExpression;
use lispa\amos\tag\AmosTag;

BaseAsset::register($this);

ModuleDashboardAsset::register($this);

AmosIcons::map($this);

$this->title = $this->context->module->name;

$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="container">
    <input type="hidden" id="saveDashboardUrl"
           value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>
    <input type="hidden" id="currentDashboardId" value="<?= $currentDashboard['id'] ?>"/>
    <?php
    //DA SOSTITUIRE
    ?>
    <div id="dashboard-edit-toolbar" class="hidden">
        <?=
        Html::a(AmosTag::t('amostag', 'Salva'), 'javascript:void(0);', [
            'id' => 'dashboard-save-button',
            'class' => 'btn btn-success bk-saveOrder',
        ]);
        ?>

        <?=
        Html::a(AmosTag::t('amostag', 'Annulla'), \yii\helpers\Url::current(), [
            'class' => 'btn btn-danger bk-saveDelete',
        ]);
        ?>

    </div>

    <?php
    /*
     * @$widgetsIcon elenco dei plugin ad icona
     * @$widgetsGrafich elenco dei plugin ad grafici
     * @$dashboardsNumber numero delle dashboard da mostrare
     */

    ?>

    <nav data-dashboard-index="<?= $currentDashboard->slide ?>">

        <div class="row actions-dashboard-container">
            <ul id="widgets-icon" class="bk-sortableIcon plugin-list"
                role="menu">
                <?php
                //indice di questa dashboard
                $thisDashboardIndex = 'dashboard_' . $currentDashboard->slide;

                //recupera i widgets di questa dashboard
                $thisDashboardWidgets = $currentDashboard->amosWidgetsSelectedIcon;

                if ($thisDashboardWidgets && count($thisDashboardWidgets) > 0) {

                    foreach ($thisDashboardWidgets as $widget) {
                        $widgetObj = Yii::createObject($widget['classname']);
                        echo $widgetObj::widget();
                    }
                } else {
                    AmosTag::t('amostag', 'Non ci sono widgets selezionati per questa dashboard');
                }
                ?>
            </ul>
        </div>

    </nav>
</div>
