<?php

namespace dizews\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class CrudButtons extends Widget
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var string Current action id.
     */
    public $actionId;

    /**
     * @var array List of buttons actions.
     */
    public $actions = [];

    /**
     * @var string
     */
    public $createTemplate = '<span class="glyphicon glyphicon-plus"></span>';

    /**
     * @var string
     */
    public $updateTemplate = '<span class="glyphicon glyphicon-pencil"></span>';

    /**
     * @var string
     */
    public $deleteTemplate = '<span class="glyphicon glyphicon-trash"></span>';

    /**
     * @var string
     */
    public $multiUpdateTemplate = '<span class="glyphicon glyphicon-pencil"></span>';

    /**
     * @var string
     */
    public $multiDeleteTemplate = '<span class="glyphicon glyphicon-trash"></span>';

    /**
     * @var array The internalization configuration for this widget.
     */
    public $i18n = [];

    /**
     * @var string|Closure Translated model name depends on actions.
     */
    public $modelName;


    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::setAlias('@crud-buttons', dirname(__FILE__));
        if (!$this->i18n) {
            $this->i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@crud-buttons/messages'
            ];
        }
        Yii::$app->i18n->translations['crud-buttons'] = $this->i18n;

        parent::init();
        if (!$this->actionId) {
            $this->actionId = Yii::$app->controller->action->id;
        }
        if (!$this->actions) {
            $actions = ['index', 'create', 'update', 'delete', 'multi-update', 'multi-delete'];
            $this->actions = array_combine($actions, $actions);
        }

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->actionId != $this->actions['create']) {
            echo ' '. Html::a($this->createTemplate, [$this->actions['create']],
                [
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('crud-buttons', 'Create {modelName}', [
                        'modelName' => $this->getModelName($this->actions['create'])
                    ]),
                ]
            );
        }

        if ($this->model) {
            if ($this->actionId != $this->actions['update']) {
                echo ' '. Html::a($this->updateTemplate, [$this->actions['update'], 'id' => $this->model->id],
                    [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('crud-buttons', 'Update {modelName} #{id}', [
                            'modelName' => $this->getModelName($this->actions['update']),
                            'id' => $this->model->id
                        ]),
                    ]
                );
            }

            $modelName = $this->getModelName($this->actions['delete']);
            echo ' '. Html::a($this->deleteTemplate, [$this->actions['delete'], 'id' => $this->model->id],
                [
                    'class' => 'btn btn-danger',
                    'title' => Yii::t('crud-buttons', 'Delete {modelName} #{id}', [
                            'modelName' => $modelName,
                            'id' => $this->model->id
                        ]),
                    'data' => [
                        'method' => 'post',
                        'confirm' => Yii::t('crud-buttons', 'Are you sure you want to delete this {modelName} #{id}', [
                            'modelName' => $modelName,
                            'id' => $this->model->id
                        ])
                    ]
                ]
            );
        } elseif ($this->actionId == $this->actions['index']) {
            if ($this->hasAction($this->actions['multi-update'])) {
                echo ' '. Html::a($this->multiUpdateTemplate, [$this->actions['multi-update'], 'ids[]' => ''],
                    [
                        'class' => 'btn btn-primary btn-multi-update',
                        'disabled' => 'disabled',
                        'title' => Yii::t('crud-buttons', 'Update some {modelName}', [
                            'modelName' => $this->getModelName($this->actions['multi-update']),
                        ]),
                    ]
                );
            }

            if ($this->hasAction($this->actions['multi-delete'])) {
                $modelName = $this->getModelName($this->actions['multi-delete']);
                echo ' '. Html::a($this->multiDeleteTemplate, [$this->actions['multi-delete'], 'ids[]' => ''],
                    [
                        'class' => 'btn btn-danger btn-multi-delete',
                        'disabled' => 'disabled',
                        'title' => Yii::t('crud-buttons', 'Delete some {modelName}', [
                            'modelName' => $modelName
                        ]),
                        'data' => [
                            'method' => 'post',
                            'confirm' => Yii::t('crud-buttons', 'Are you sure you want to delete some {modelName}?', [
                                'modelName' => $modelName
                            ])
                        ]
                    ]
                );
            }
        }

    }

    /**
     * Returns a value indicating whether a controller action is defined.
     *
     * @param string $id Action id
     * @return bool
     */
    protected function hasAction($id)
    {
        $actionMap = Yii::$app->controller->actions();
        if (isset($actionMap[$id])) {
            return true;
        } elseif (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
            if (method_exists(Yii::$app->controller, $methodName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $actionId
     * @return Closure|mixed|string
     */
    protected function getModelName($actionId)
    {
         if ($this->modelName instanceof \Closure) {
             return call_user_func($this->modelName, $actionId);
         }

        return $this->modelName;
    }


} 