Crud buttons Widget
===================

It is a widget for Yii2 framework which control a crud buttons.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dizews/yii2-crud-buttons "0.1.*"
```

or add

```
"dizews/yii2-crud-buttons": "0.1.*"
```

to the require section of your composer.json.

General Usage
-------------

```php
<?= CrudButtons::widget(['model' => isset($model) ? $model : null]); ?>
```

If you want to get more understandable titles of buttons you should set ```modelName```.

```php
<?= CrudButtons::widget(['model' => isset($model) ? $model : null,
    'modelName' => function ($actionId) {
        $count = 1;
        if ($actionId == 'multi-update' || $actionId == 'multi-delete') {
            $count = 10; //any number for plural
        }
        return Yii::t('app', '{n, plural, =1{User} other{Users}}', ['n' => $count])
    }
]); ?>
```
