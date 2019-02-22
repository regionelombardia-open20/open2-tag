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

class WidgetIconTag extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosTag::tHtml('amostag', 'Tag'));
        $this->setDescription(AmosTag::t('amostag', 'Elenco dei widgets del plugin Tag'));

        $this->setIcon('tag');
        //$this->setIconFramework();

        $this->setUrl(Yii::$app->urlManager->createUrl(['tag']));
        $this->setCode('TAG_MODULE');
        $this->setModuleName('tag');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-secondary'
        ]));
    }

    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    /* TEMPORANEA */
    public function getWidgetsIcon()
    {
        $widgets = [];

        //istanza di MyProfile
        $TagManager = new WidgetIconTagManager();
        if ($TagManager->isVisible())
            $widgets[] = $TagManager->getOptions();

        return $widgets;
    }
}