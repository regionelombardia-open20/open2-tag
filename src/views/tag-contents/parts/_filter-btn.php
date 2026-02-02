<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var $this \yii\web\View
 * @var $modelSearch \open20\amos\core\record\ContentModel
 */

$classSearch = $modelSearch->formName();
$countFilterActive = 0;
$filterActiveClass = 'btn-secondary';
foreach (Yii::$app->request->queryParams[$classSearch] as $attribute => $val) {
    if (!empty($val) && $attribute != 'orderAttribute' && $attribute != 'orderType') {
        if ($attribute == 'tagValues') {
            if (!in_array(null, $val, true) && !in_array('', $val, true)) {
                $countFilterActive++;
            }
        } else {
            $countFilterActive++;
        }
    }
}
$htmlCountFilter = '<span class="m-l-5">' . Yii::t('amoslayout', 'Filtri') . '</span>';
$tooltipText = Yii::t('amoslayout', 'Azzera opzioni di ricerca');
if ($countFilterActive > 0) {
    $htmlCountFilter = "<span class='m-l-5'>" . Yii::t('amoslayout', 'Filtri') . " ($countFilterActive)</span>";
    $filterActiveClass = 'btn-secondary-outline active-filter';
    $tooltipText = Yii::t('amoslayout', 'Filtri di ricerca applicati: {x}', [
        'x' => $countFilterActive
    ]);
} ?>

    <!-- Filter button -->
<?= Html::tag('div', AmosIcons::show('tune', []) . $htmlCountFilter, [
    'class' => 'btn-group btn show-hide-element ' . $filterActiveClass,
    'data-toggle-element' => 'form-search',
    'title' => $tooltipText,
    //'data-toggle' => 'tooltip'
    'tabindex' => 0
]); ?>