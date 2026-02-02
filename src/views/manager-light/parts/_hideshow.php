<?php

/**
 * @var $model
 * @var $nOccurrencies
 * @var $id
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\tag\AmosTag;


$js = <<<JS
 $('#save-button').on('click', function (e) {
        e.preventDefault(); 
        var formData = $('.tag-aform').serialize();
        
        $.ajax({
            url:'/tag/manager-light/hide-show?id=$id',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response) {
                    detailTree('$parent_id'); 
                    $('#modalHideShowTag').modal('hide');
                } 
            },
        });
    });

JS;
$this->registerJs(
    $js,
    \yii\web\View::POS_END
);


if (!$model->disabled) {
    if (count($children) > 0) {
        $titleAssegnati = AmosTag::t('amostag',
                '<p>Nascondendo il ramo di tag "{label}" {x} {y}</p>'
            .  "<p><strong class='text-danger'>Nota bene:</strong> questa operazione <u>non</u> eliminerà il tag, ma lo renderà non selezionabile dagli utenti, che non potranno più interagirvi o salvarlo.</p>", [
            'label' => $model->nome,
            'x' => count($children) == 1 ? AmosTag::t('amostag',', verrà reso invisibile e non selezionabile anche il tag al suo interno') : AmosTag::t('amostag',', tutti i<strong> {x} tag </strong> al suo interno verranno nascosti',['x' => count($children)]),
			'y' => $nOccurrencies > 0 ? AmosTag::t('amostag', ', impattando su un totale di <strong>{y} contenuti</strong><br>', ['y' => $nOccurrencies]) : '.<br>',
        ]);
    } else {
        $titleAssegnati = AmosTag::t('amostag', '<p>Nascondendo il tag "{label}", verrà reso non selezionabile.</p>'
            .  "<p><strong class='text-danger'>Nota bene:</strong> questa operazione <u>non</u> eliminerà il tag, ma lo renderà non selezionabile dagli utenti, che non potranno più interagirvi o salvarlo.</p>", [
            'label' => $model->nome,
        ]);
    }
} else {
    if (count($children) > 0) {
        $titleAssegnati = AmosTag::t('amostag', '<p>Vuoi mostrare nuovamente il tag selezionato?<p>'
                . '<p>Mostrando il ramo di tag "{label}"{x}.</p>'
            . "<p><strong>Nota bene:</strong> gli utenti interessati a questi tag possono ricevere nuovamente notifiche a pubblicazione avvenuta (in base alle proprie impostazioni).</p>", [
            'label' => $model->nome,
            'x' => count($children) == 1 ?  AmosTag::t('amostag',',  verranno resi nuovamente selezionabili tutti i<strong> {x} tag </strong> al suo interno',['x' => count($children)]): AmosTag::t('amostag','verrà reso visibile e selezionabile anche il tag al suo interno'),
        ]);
    } else {
        $titleAssegnati = AmosTag::t('amostag', '<p>Mostrando il tag "{label}", verrà reso nuovamente visibile e selezionabile.</p>'
            . "<strong>Nota bene:</strong> questa operazione riabiliterà la possibilità di assegnare il tag a nuovi contenuti, così che gli utenti interessati possono ricevere"
            . " nuovamente notifiche a pubblicazione avvenuta (in base alle proprie impostazioni).", [
            'label' => $model->nome,
        ]);
    }
}

$form = ActiveForm::begin([
    'options' => [
        'class' => 'tag-aform',
    ]
]);
?>
<div class="tag-form">

    <div>
        <?= $titleAssegnati ?>
    </div>
    <?= $form->field($model, 'disabled')->hiddenInput(['value' => !$model->disabled])->label(false) ?>
    <div class="actions-modal-tag m-t-30">
        <div class="text-right">
            <button type="reset" class="btn btn-secondary" data-dismiss="modal" title="<?= AmosTag::t('amostag', 'Annulla') ?>">
                <?= AmosTag::t('amostag', 'Annulla') ?>
            </button>
            <button type="button" id="save-button" class="btn <?= $model->disabled ? 'btn-primary' : 'btn-danger-inverse'  ?>" title="<?= AmosTag::t('amostag', $model->disabled ? 'Mostra' : 'Nascondi') ?>">
                <?= AmosTag::t('amostag', $model->disabled ? 'Mostra Tag' : 'Nascondi tag') ?>
            </button>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>