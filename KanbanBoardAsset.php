<?php
namespace igor162\KanbanBoard;

use Yii;

/**
 * KanbanBoard AssetBundle
 * @since 1.2.2
 */
class KanbanBoardAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/igor162/yii2-kanban-board/assets';

    public $css = [
        'css/kanban.css',
        'vendor/open-iconic/css/open-iconic-bootstrap.min.css',
        'vendor/fontawesome/css/all.css',
    ];
    public $js = [
        'vendor/sortablejs/Sortable.min.js',
    ];
}
