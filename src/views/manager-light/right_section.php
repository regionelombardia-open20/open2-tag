<?php

/**
 * @var $models [] \open20\amos\tag\models\Tag
 * @var $countSelected
 * @var $occurrencies
 */
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\Tag;
?>
<div class="box-tag-grey">
    <div class="box-tags-selected">
        <div class="box-parent-tag">

            <div class="head-parent-tag">
                <div class="label-number-tag-selected">
                    <span class="text-truncate m-r-5"><?= AmosTag::t('amostag', "Tag selezionati") . ': ' ?></span>
                    <strong class="nSelectedTags"><?= $countSelected ?></strong>
                </div>
            </div>
            <div>
                <?php
                if ($countSelected > 0) {
                    $ids = [];
                    foreach ($models as $model) {
                        $ids[] = $model->id;
                        echo $this->render('parts/_selected_tags', [
                            'tagName' => $model->nome,
                            'root' => $model->root,
                            'id' => $model->id,
                            'path' => '/ ' . $model->getPath(false),
                            'disabled' => $model->disabled,
                            'readonly' => $model->readonly,
                            'occurrencyTags' => Tag::getOccurrencyTags([$model->root], null, $model->id),
                        ]);
                    }
                } else {
                    ?>
                    <p class="no-selected-tag"><?= AmosTag::t('amostag', "Nessun tag selezionato") ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="box-dati-tags">
            <div class="label-dato-tags">
                <span class="mdi mdi-bookmark-multiple mdi-24px m-r-5"></span>
                <span><?= AmosTag::t('amostag', "Contenuti in cui compaiono") ?></span>
                <a href="javascritp:void(0)" title="<?= AmosTag::t('amostag', "Numero di contenuti in cui compaiono i tag selezionati") ?>" data-toggle="toolitp" class="text-secondary m-r-5 m-l-5"><span class="mdi mdi-information-outline"></span></a>
                <strong><?= $occurrencies ?></strong>
            </div>
            <div class="label-dato-tags">
                <span class="mdi mdi-account-supervisor-circle mdi-24px m-r-5"></span>
                <span><?= AmosTag::t('amostag', "Potenziali utenti raggiunti") ?></span>
                <a href="javascritp:void(0)" title="<?= AmosTag::t('amostag', "Numero di potenziali utenti raggiunti con i tag selezionati") ?>" data-toggle="toolitp" class="text-secondary m-r-5 m-l-5"><span class="mdi mdi-information-outline"></span></a>
                <strong><?= $userInterestsCount ?></strong>
            </div>
        </div>
    </div>
    <div class="footer-box-tags-selected">
        <?php
        echo \yii\helpers\Html::a('<span class="mdi mdi-eye-off m-r-5"></span>' .
                AmosTag::t('amostag', AmosTag::t('amostag', 'Nascondi selezionati')),
                "#",
                [
                    'class' => $all_disabled == 1 ? 'btn btn-danger-inverse m-t-5 disabled' : 'btn btn-danger-inverse m-t-5',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalMultipleHideShowTag',
                    'data-id' => $ids,
                    'data-disabled' => 0,
                    'data-occurrencies' => $occurrencies,
                    'data-parent_id' => $parent_id,
                    'id' => 'linkMultipleHideShowTag-0',
                ]
        );
        ?>
        
        <?php
        echo \yii\helpers\Html::a('<span class="mdi mdi-eye m-r-5"></span>' .
                AmosTag::t('amostag', AmosTag::t('amostag', 'Mostra selezionati')),
                "#",
                [
                    'class' => $all_active == 1 ? 'btn btn-outline-primary m-t-5 disabled' : 'btn btn-outline-primary m-t-5',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalMultipleHideShowTag',
                    'data-id' => $ids,
                    'data-disabled' => 1,
                    'data-occurrencies' => $occurrencies,
                    'data-parent_id' => $parent_id,
                    'id' => 'linkMultipleHideShowTag-1',
                ]
        );
        ?>
    </div>
</div>