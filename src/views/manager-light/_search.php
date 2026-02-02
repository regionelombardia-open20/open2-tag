<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operations/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;


/**
 * @var yii\web\View $this
 * @var backend\modules\operations\models\search\PoiCookiePolicyPagesSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="poi-cookie-policy-pages-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- url_page -->
    <div class="col-md-4"> <?=
        $form->field($model, 'nome')->textInput(['placeholder' => 'inizia a digitare per cercare i tag']) ?>
    </div>
    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Annulla'), ['/tags/manager-light/index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
