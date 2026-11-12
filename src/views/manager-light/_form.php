<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-tag
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

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
