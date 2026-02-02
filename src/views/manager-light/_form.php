<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\tag\AmosTag;

$url = '/tag/manager-light/update-tree?id=' . $id;
if ($model->isNewRecord) {
    $url = '/tag/manager-light/create-tree?id=' . $id;
}

$js = <<<JS
 $('#save-button').on('click', function (e) {
        e.preventDefault(); 
        var formData = $('.tag-aform').serialize();
        
        $.ajax({
            url:'$url',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response) {
                    detailTree('$parent_id'); 
                    $('#modalCreaNuovoTag').modal('hide');
                    $('#modalUpdateTag').modal('hide');
                } 
            },
        });
    });

JS;
$this->registerJs($js
        , \yii\web\View::POS_END);

$form = ActiveForm::begin([
            'options' => [
                'class' => 'tag-aform',
            ]
        ]);
?>
<div class="tag-form">
    
    <div class="mt-4" >
        <div class="" id="informazioni">
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'id')->textInput(['readonly' => true, 'placeholder' => AmosTag::t('amostag', "Id")])->label(AmosTag::t('amostag', "Id")) ?>
                </div>
                <div class="col-sm-8">
                    <?= $form->field($model, 'nome')->textInput(['placeholder' => AmosTag::t('amostag', "Nome *"), 'required' => true])->label(AmosTag::t('amostag', "Nome")) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'descrizione')->textArea(['rows' => 3])->label(AmosTag::t('amostag', "Descrizione")) ?>
                </div>
            </div>
        </div> 
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <div class="actions-modal-tag">
        <div class="d-flex">
            <button type="reset" class="btn btn-secondary mr-auto" data-dismiss="modal" title="<?= AmosTag::t('amostag', 'Reset') ?>">
            <?= AmosTag::t('amostag', "Annulla") ?>
            </button>
            <button type="button" id="save-button" class="btn btn-primary" title="<?= AmosTag::t('amostag', 'Save') ?>">
                <span class="mdi mdi-content-save-outline m-r-5"></span><?= AmosTag::t('amostag', "Salva") ?>
            </button>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>
