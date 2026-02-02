<?php

/**
 * @var $model
 * @var $nOccurrencies
 * @var $id
 */
use open20\amos\core\forms\ActiveForm;
use open20\amos\tag\AmosTag;
use yii\helpers\Html;

$js = <<<JS
 $('#save-button').on('click', function (e) {
        e.preventDefault(); 
        var formData = $('.tag-aform').serialize();
        
        $.ajax({
            url:'/tag/manager-light/multiple-hide-show',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response) {
                    detailTree('$parent_id'); 
                    $('#modalMultipleHideShowTag').modal('hide');
                } 
            },
        });
    });

JS;
$this->registerJs(
        $js,
        \yii\web\View::POS_END
);

if (!$disabled) {
    $titleAssegnati = AmosTag::t('amostag', '<p>Nascondendo i tag selezionati, non saranno più applicabili ai nuovi contenuti.</p>'
                    . "<p><strong class='text-danger'>Nota bene:</strong> questa operazione non eliminerà il tag, ma farà si che non sarà più selezionabile per i nuovi contenuti.<br>Se i tag sono rami, verrà applicato anche a tutti i loro figli</p>");
} else {

    $titleAssegnati = AmosTag::t('amostag', '<p>Mostrando i tag selezionati, verranno resi nuovamente visibili e selezionabili.<br>Se i tag sono rami, verrà applicato anche a tutti i loro figli</p>'
                    . "<strong>Nota bene:</strong> questa operazione riabiliterà la possibilità di assegnare il tag a nuovi contenuti, così che gli utenti interessati possono ricevere"
                    . "nuovamente notifiche a pubblicazione avvenuta(in base alle proprie impostazioni)");
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
    <?php foreach ($ids as $id): ?>
        <?= Html::hiddenInput('ids[]', $id); ?>
    <?php endforeach; ?>
    <?= Html::hiddenInput('disabled', !$disabled); ?>
    <div class="actions-modal-tag m-t-30">
        <div class="text-right">
            <button type="reset" class="btn btn-secondary" data-dismiss="modal" title="<?= AmosTag::t('amostag', 'Annulla') ?>">
                <?= AmosTag::t('amostag', 'Annulla') ?>
            </button>
            <button type="button" id="save-button" class="btn <?= $disabled ? 'btn-primary' : 'btn-danger-inverse' ?>" title="<?= AmosTag::t('amostag', $disabled ? 'Mostra' : 'Nascondi') ?>">
                <?= AmosTag::t('amostag', $disabled ? 'Mostra Tag' : 'Nascondi tag') ?>
            </button>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>