<?php

use yii\db\Migration;
use lispa\amos\dashboard\models\AmosWidgets;

class m170919_132221_change_widget_level extends Migration
{
    const MODULE_NAME = 'tag';

    /**
     * @return bool
     */
    public function safeUp()
    {
        if ($this->checkWidgetExist(\lispa\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
            [
             'dashboard_visible' => 1,
              'child_of' => ''
            ],[
                'classname' => \lispa\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\lispa\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'status' => AmosWidgets::STATUS_DISABLED,
                ],[
                    'classname' => \lispa\amos\tag\widgets\icons\WidgetIconTag::className()
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

        if ($this->checkWidgetExist(\lispa\amos\tag\widgets\icons\WidgetIconTagManager::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 0,
                    'child_of' => \lispa\amos\tag\widgets\icons\WidgetIconTag::className()
                ],[
                    'classname' => \lispa\amos\tag\widgets\icons\WidgetIconTagManager::className()
                ]);
        }
        if ($this->checkWidgetExist(\lispa\amos\tag\widgets\icons\WidgetIconTag::className())) {

            $this->update(AmosWidgets::tableName(),
                [
                    'dashboard_visible' => 1,
                    'status' => AmosWidgets::STATUS_ENABLED,
                ],[
                    'classname' => \lispa\amos\tag\widgets\icons\WidgetIconTag::className()
                ]);
        }

        return true;
    }
}
