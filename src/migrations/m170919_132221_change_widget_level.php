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

use yii\db\Migration;
use open20\amos\dashboard\models\AmosWidgets;

class m170919_132221_change_widget_level extends Migration
{
    const MODULE_NAME = 'tag';

    /**
     * @return bool
     */
    public function safeUp()
    {
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
            [
             'dashboard_visible' => 1,
              'child_of' => ''
            ],[
                'classname' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'status' => AmosWidgets::STATUS_DISABLED,
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ]);
        }
        return true;
    }


    /**
     * @param $classname
     * @return mixed
     */
    private function checkWidgetExist($classname)
    {

        return AmosWidgets::find()
            ->andWhere([
                'classname' => $classname
            ])->count();
    }

    /**
     * @return bool
     */
    public function safeDown()
    {

        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'child_of' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\open20\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 1,
                    'status' => AmosWidgets::STATUS_ENABLED,
                ],[
                    'classname' => \open20\amos\tag\widgets\icons\WidgetIconTag::className()
                ]);
        }

        return true;
    }
}
