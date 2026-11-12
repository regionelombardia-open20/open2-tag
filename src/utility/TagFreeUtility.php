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

namespace open20\amos\tag\utility;

use open20\amos\tag\models\TagFree;
use open20\amos\tag\AmosTag;
use open20\amos\tag\models\TagFreeModelMm;
use Exception;

/**
 * Description of TagFreeUtility
 *
 */
class TagFreeUtility {

    /**
     * 
     * @param int $id
     * @param string $classname
     * @param string $tags
     * @return boolean
     */
    public static function set($id, $classname, $tags) {
        try {

            $module = AmosTag::instance();
            if (array_key_exists($classname, $module->modelsTagFree)) {
                $model_id = $module->modelsTagFree[$classname];
                $tag_free = explode(',', $tags);
                TagFreeModelMm::deleteAll(['content_id' => $id, 'model_id' => $model_id]);
                foreach ($tag_free as $tag) {
                    $new = false;
                    $exists = TagFree::find()->andWhere(['tag' => $tag])->one();
                    if (empty($exists)) {
                        $new = true;
                        $exists = new TagFree();
                        $exists->tag = $tag;
                        $exists->counter = 1;
                        $exists->save(false);
                    }

                    $tagModelMm = new TagFreeModelMm();
                    $tagModelMm->tag_id = $exists->id;
                    $tagModelMm->content_id = $id;
                    $tagModelMm->model_id = $model_id;
                    $tagModelMm->save(false);
                }
            }
            return true;
        } catch (Exception $ex) {
            \Yii::getLogger()->log('Errore: ' . $ex->getMessage() . ' - file: ' . $ex->getFile() . ' - linea: ' . $ex->getLine(), 
                     \yii\log\Logger::LEVEL_ERROR);
            return false;
        }
    }

    /**
     * 
     * @param int $id
     * @param string $classname
     * @return string
     */
    public static function get($id, $classname) {
        $tags = '';
        try {

            $module = AmosTag::instance();
            if (array_key_exists($classname, $module->modelsTagFree)) {
                $model_id = $module->modelsTagFree[$classname];
                $arrTag = TagFreeModelMm::find()
                        ->innerJoin('tag_free', 'tag_free.id = tag_free_model_mm.tag_id')
                        ->andWhere(['model_id' => $model_id])
                        ->andWhere(['content_id' => $id])
                        ->select('tag_free.tag')
                        ->column();
                if (!empty($arrTag)) {
                    $tags = implode(',', $arrTag);
                }
            }
            return $tags;
        } catch (Exception $ex) {
             \Yii::getLogger()->log('Errore: ' . $ex->getMessage() . ' - file: ' . $ex->getFile() . ' - linea: ' . $ex->getLine(), 
                     \yii\log\Logger::LEVEL_ERROR);
            return '';
        }
    }

}
