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
<?= CrudButtons::widget(['model' => $model]); ?>
```

If you want to get more understandable titles of buttons you should set ```modelName```.

```php
<?= CrudButtons::widget(['model' => $model,
    'modelName' => function ($actionId) {
        $count = 1;
        switch ($actionId) {
            case 'multi-update':
            case 'multi-delete':
                $count = 10;
                break;
        }
        Yii::t('app', '{n, plural, =1{User} other{Users}}', ['n' => $count])
    }
]); ?>
```
