Introduction 
======

> **KanbanBoard v1.2.2** -- KanbanBoard is project management software that focuses on the Kanban methodology. Based on **[Bootstrap v4.3.1](https://github.com/twbs/bootstrap)** CSS framework. 
It can be used with **[Yii2 v2.0.16](https://github.com/yiisoft/yii2)**


Installation
------------
There are multiple ways to install KanbanBoard.

####Using The Command Line:

**Github**

- Fork the repository ([here is the guide](https://help.github.com/articles/fork-a-repo/)).
- Clone to your machine

```
git clone https://github.com/igor162/yii2-kanban-board.git
```

**Composer**

```
composer require "igor162/yii2-kanban-board" "dev-master"
```

**Example**

```php
<?php

use igor162\KanbanBoard\widgets\KanbanWidget;
use igor162\adminlte\widgets\Box;

?>
<?php Box::begin([
    'type' => false,
    'title' => false,
    'footer' => false
]); ?>

<?= KanbanWidget::widget([
    'data' => $data,
//    'dataRoute' => Url::toRoute(['task/task-list'/*, 'form' => Disguise::FORM_TYPE_AJAX, 'returnUrl' => Helper::getReturnUrl()*/]),
    'showAddTask' => KanbanWidget::SHOW_ONE, // KanbanWidget::SHOW_ALL
    'editorURLTaskStatus' => ['task-status/update', 'form' => TaskStatus::FORM_TYPE_AJAX, 'returnUrl' => Helper::getReturnUrl()],
    'addURLTask' => ['task/update', 'form' => TaskStatus::FORM_TYPE_AJAX, 'returnUrl' => Helper::getReturnUrl()],
    'editorURLTask' => ['task/update', 'form' => Task::FORM_TYPE_AJAX, 'returnUrl' => Helper::getReturnUrl()],
    'changeURLTaskAction' => ['menuChangeTaskPosition'],
    'archiveURLTaskAction' => ['task/archive'],
    'editorHeaderStatus' => true,
    'cardFooterMenuItems' => [
        'show_history' => [
            'typeButton' => true,
            'label' => false,
            'icon' => 'oi oi-comment-square mr-1',
            'small' => true,
            'options' => [
                'class' => 'card-footer-item card-footer-item-bordered text-muted',
                'data-toggle' => 'modal',
                'data-target' => '#modalViewTask',
                'draggable' => 'false',
                'title' => 'История операций',
            ],
            /*                'action' => ContextMenuHelper::actionUrl(
                                ['shop-stuff/update', 'returnUrl' => Helper::getReturnUrl()],
                                [
                                    'path' => 'id',
                                ]
                            ),*/
        ],
        'set_reminder' => [
            'label' => false,
            'icon' => 'fa fa-history text-teal_kn',
            'small' => false,
            'options' => [
                'class' => 'card-footer-item card-footer-item-bordered text-muted',
                'draggable' => 'false',
                'title' => 'Установить Напоминание',
            ],
            /*                'action' => ContextMenuHelper::actionUrl(
                                ['shop-stuff/update', 'returnUrl' => Helper::getReturnUrl()],
                                [
                                    'path' => 'id',
                                ]
                            ),*/
        ],
        'archive' => [
            'label' => false,
            'icon' => 'fa fa-history text-teal_kn',
            'small' => false,
            'options' => [
                'class' => 'card-footer-item card-footer-item-bordered text-muted',
                'draggable' => 'false',
                'title' => 'Архивировать',
            ],
            /*                'action' => ContextMenuHelper::actionUrl(
                                ['shop-stuff/update', 'returnUrl' => Helper::getReturnUrl()],
                                [
                                    'path' => 'id',
                                ]
                            ),*/
        ],
    ]
]);
?>

<?php Box::end(); ?>

```
