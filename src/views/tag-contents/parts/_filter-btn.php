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