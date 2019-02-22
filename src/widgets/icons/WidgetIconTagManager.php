<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\tag
 * @category   CategoryName
 */

namespace lispa\amos\tag\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;
use lispa\amos\tag\AmosTag;

class WidgetIconTagManager extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosTag::tHtml('amostag', '#widget_title_tag'));
        $this->setDescription(AmosTag::t('amostag', 'Consente all\'utente di gestire gli alberi di tag'));

        $this->setIcon('tag');
        //$this->setIconFramework();

        $this->setUrl(Yii::$app->urlManager->createUrl(['tag/manager']));
        $this->setCode('TAG_MANAGER');
        $this->setModuleName('tag');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-secondary'
        ]));
    }

}