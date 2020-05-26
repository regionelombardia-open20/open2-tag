#Amos Tag

Extension for tags and interest areas.

Installation
------------

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-tag
```

or add this row

```
"open20/amos-tag": "dev-master"
```

to the require section of your `composer.json` file.

2. Add module to your main config in backend:
	
    ```php
    
    'modules' => [
        'tag' => [
            'class' => 'open20\amos\tag\AmosTag',
            'modelsEnabled' => [
                /**
                 * Add here the classnames of the models for whose you want to enable tags
                 * (i.e. 'open20\amos\news\models\News')
                 */
            ]
        ],
    ],
    ```

3. To enable user-profile interest areas (tags to match with content tags), amos-cwh installation is needed
 

4. Apply migrations

    a. amos-tag migrations
    ```bash
    php yii migrate/up --migrationPath=@vendor/open20/amos-tag/src/migrations
    ```
    
    or add this row to your migrations config in console:
    
    ```php
    return [
        .
        .
        .
        '@vendor/open20/amos-tag/src/migrations',
        .
        .
        .
    ];
    ```
    
Configuration
------------

Tag management is available for admin role - url PlatformUrl **/tag/manager**.
In tag manager it is possible to:
 - To add new trees or tags
 - enable tag trees (by root selection) for specific models - to all roles (rbac) or to the specified ones
 - change tree settings eg. root visible will allow the user to select all tags by root in TagWidget (see below) 

Widgets
-----------

Amos Tag provides two Widgets:
* **TagWidget** *open20\amos\tag\widgets\TagWidget*  
Draws tag tree to select values for a model.
example of use in a form:
```php
    echo open20\amos\tag\widgets\TagWidget::widget([
        'model' => $model,
        'attribute' => 'tagValues',
        'form' => \yii\base\Widget::$stack[0],
        'singleFixedTreeId' => $treeRoot,
        'id' => 'third-tree',
        'hideHeader' => true
    ]);
```

In a search form you can use the same widget with options :
```php
$params = \Yii::$app->request->getQueryParams();
echo \open20\amos\tag\widgets\TagWidget::widget([
    'model' => $model,
    'attribute' => 'tagValues',
    'form' => $form,
    'isSearch' => true,
    'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
]);
```

If singleFixedTreeId is not specified, all enabled trees for the $model are considered (table tag_models_auth_items_mm).
singleFixedTreeId is now possible an array (tag roots in singleFixedTreeId will be considered).

* **ShowTagsWidget** *open20\amos\tag\widgets\ShowTagsWidget*  
Draws the selected tags for a model (in view mode).
It is possible to specify a tree (property 'rootId') or a set of trees (property 'rootIdsArray') to show; if nothing is specified, all enabled trees for the model are considered. 
Example: 

```
 <?= \open20\amos\tag\widgets\ShowTagsWidget::widget([
    'model' => $model,
    'rootId' => $rootId
 ]) ?>
 ```

