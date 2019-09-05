<?php
/**
 * Created by PhpStorm.
 * User: ig
 * Date: 17.06.19
 * Time: 15:38
 */

use yii\helpers\Html;
use kartik\widgets\AlertBlock;

use igor162\KanbanBoard\widgets\KanbanWidget;
use yii\helpers\Url;
use kartik\icons\Icon;
use igor162\RemoveButton\RemoveModal;
use igor162\modal\Modal;

/* @var $array */
/* @var $optionsBoard */
/* @var $editorHeaderStatus */
/* @var $showAddTask */
/* @var $editorURLTaskStatus */
/* @var $editorURLTask */
/* @var $addURLTask */
/* @var $idModal */
/* @var $taskItemID */
/* @var $defaultTranslationCategory */
/* @var $archiveURLTaskAction */


Modal::begin([
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="text-left" style="color: #000; font-size: 20px; font-weight: 500;"></h4>',
    'closeButton' => false,
    'toggleButton' => false,
    'options' => [
        'id' => $idModal,
        'tabindex' => false // important for Select2 to work properly
    ],
]);
Modal::end();
$removeModelID = 'delete-category-confirmation'; // номер модели
?>
<?= RemoveModal::widget([
    'modelNameId' => $removeModelID,
    'bodyTittle' => [ 'label' => \Yii::t($defaultTranslationCategory, 'Are you sure you want to archive this item?')],
    'footerButton' => [
        'encode' => false,
        'labelDelete' => [
            'label' => \Yii::t($defaultTranslationCategory, 'Yes'),
            'class' => Modal::STYLE_DANGER,
        ],
        'labelCancel' => [
            'label' => \Yii::t($defaultTranslationCategory, 'No'),
            'class' => Modal::STYLE_DEFAULT,
        ],
    ]
]); ?>

<?= AlertBlock::widget([
    'useSessionFlash' => true,
    'type' => AlertBlock::TYPE_GROWL,
]); ?>

<?= Html::beginTag('div', $optionsBoard);?>

<?php foreach($array as $id => $val): ?>

<?php


/*    return Html::a(Icon::show('trash-o'), null, [
        'data-url' => Url::toRoute(['delete', 'id' => $model->id_brute]),
        'onclick' => "$('#delete-category-confirmation').attr('data-url', $(this).attr('data-url')).removeAttr('data-items').modal('show');",
        'title' => \Yii::t('app', 'Delete of «{attribute}» #{item}', ['attribute' => \Yii::t('app', 'Brute Force Account'), 'item' => $model->type]),
    ])*/;
/*    function (data) {
        $(".modal-body").html(data);
        $("#modal-card").modal("show")
        .find(".modal-header h4").text("{$titleNameCreate}")
        ;
    }
 $("#modal-card").modal("show")
                                        .find(".modal-header h4").text("' . \Yii::t('app', 'Search by parameters') . '").end()
                                        .find(".modal-dialog").removeClass().addClass("modal-dialog ' . Modal::SIZE_LARGE . '").end()
                                        .find("#modalContent-card")
                                        .load($(this).attr("value"));



*/
//            print_r($val);
    $idTaskStatus = isset($val['id']) ? $val['id'] : null;
    $fixedTaskStatus = isset($val['fixed']) ? $val['fixed'] : false;
    $taskMoving = 'taskMoving_'.$id;
//            print_r($idTaskStatus);
    $name = isset($val['name']) ? $val['name'] : null;
    $countTask = !is_array($val['tasks']) ? count($val['tasks']):  count($val['tasks']);
    $arrayTask = !is_array($val['tasks']) ? null :  $val['tasks'];
//            $showStatus = !($val['show']) ? ' hidden-tasks webix_accordionitem collapsed' : ''; // Hide task block
//            $showTaskHeader = !($val['show']) ? 'webix_accordionitem_header hidden-tasks-header collapsed' : ''; // Скрыть
    $showStatus = '';
    $showTaskHeader = '';
//            echo $showStatus.'<br>';
//            echo '<pre>'.print_r($val,true).'</pre>';

    echo Html::beginTag('div', ['class' => 'tasks'.$showStatus,'draggable' => 'false']);

    echo Html::beginTag('div', ['class' => 'task-header'.$showTaskHeader]);


//            echo Html::tag('div','<i class="fa fa-caret-square-o-right fa-5"></i>',['class' => 'webix_accordionitem_button']);
//            echo Html::tag('div', Html::encode($name) ,['class' => 'webix_accordionitem_label']);

    if($editorHeaderStatus){
       // add "id" model to change
        $editorURLTaskStatusChange = is_array($editorURLTaskStatus) ? Url::to(array_merge($editorURLTaskStatus,['id' => $idTaskStatus])) : $editorURLTaskStatus . "?id=$idTaskStatus";
        echo Html::tag('h3',
            Html::a(Html::encode($name).' '.Html::tag('span','('.$countTask.')', ['class' => 'badge_kn text-muted']), '#', ['data-url' => $editorURLTaskStatusChange, /*'data-toggle' => 'modal','data-target' => '#modalKanban',*/'draggable' => 'false', 'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"data-url\"));",])
            ,['class' => 'task-title mr-auto']);
    }else{
        echo Html::tag('h3',Html::encode($name).' '.Html::tag('span','('.$countTask.')') ,['class' => 'task-title mr-auto']);
    }

    $addURLTaskChange = is_array($addURLTask) ? Url::to(array_merge($addURLTask,['status_id' => $idTaskStatus])) : $addURLTask . "?id=$idTaskStatus";

    if($showAddTask === KanbanWidget::SHOW_ONE && $id === 0 ){
        echo Html::button(Icon::show('plus-circle'), [
            'value' => $addURLTaskChange,
            'class' => 'btn btn-light btn-icon text-muted',
            'title' => \Yii::t($defaultTranslationCategory, 'Add Task'),
            'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"value\"));"
        ]);
    }elseif ($showAddTask === KanbanWidget::SHOW_ALL){
        echo Html::button(Icon::show('plus-circle'), [
            'value' => $addURLTaskChange,
            'class' => 'btn btn-light btn-icon text-muted',
            'title' => \Yii::t($defaultTranslationCategory, 'Add Task'),
            'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"value\"));"
        ]);
    }
    ?>

<!--    --><?// if($editorHeaderStatus): ?>
<!--        --><?//= Html::tag('h3',
//            Html::a(Html::encode($name).' '.Html::tag('span','('.$countTask.')', ['class' => 'badge_kn text-muted']), ['update','id' => $id], ['data-toggle' => 'modal','data-target' => '#modalViewTask','draggable' => 'false'])
//            ,['class' => 'task-title mr-auto']); ?>
<!--    --><?// else: ?>
<!--        --><?//= Html::tag('h3',Html::encode($name).' '.Html::tag('span','('.$countTask.')') ,['class' => 'task-title mr-auto']);?>
<!--    --><?// endif; ?>
<?= Html::endTag('div');?>
<?= Html::beginTag('div', ['id' => "$taskMoving",'data-value' => "$idTaskStatus",'class' => 'task-body','data-toggle' => 'sortable','data-group' => 'tasks','data-delay' => '50','data-force-fallback' => 'true']);?>

    <?php foreach($arrayTask as $idTask => $valTask): ?>
        <? $taskNameID = isset($valTask['id_task']) ? $taskItemID . $valTask['id_task'] : null; ?>
        <? $taskID = isset($valTask['id_task']) ? $valTask['id_task'] : null; ?>
        <? $editorURLTaskChange = is_array($editorURLTask) ? Url::to(array_merge($editorURLTask,['id' => $taskID])) : $editorURLTask . "?id=$taskID";

//        echo '<pre>'.print_r($valTask,true).'</pre>';
        ?>
        <div class="task-issue" id="<?=$taskNameID?>" data-value="<?=$taskID?>">
            <!-- .card -->
            <div class="card">
                <div class="task-label-group">
                    <? if($color = $valTask['color']){ echo '<span class="task-label '.$color.'"></span>'; } ?>
                </div>
                <!-- .card-header -->
                <div class="card-header">
                    <h4 class="card-title">
                        <?
                            $name = isset($valTask['name']) ? strip_tags($valTask['name']) : null;
                            $name = strlen($name) > 63 ? mb_substr($name, 0, 60).'...' : $name;
                            $name = Html::encode($name);
                        ?>
                        <?= Html::a($name . ' #' . $valTask['id_task'], '#', ['data-url' => $editorURLTaskChange, 'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"data-url\"));",/*'data-toggle' => 'modal','data-target' => '#modalKanban',*/'draggable' => 'false']); ?>
                    </h4>
                    <h6 class="card-subtitle text-muted">
                        <?
                        $date = isset($valTask['updated_at']) ? $valTask['updated_at'] : $valTask['created_by'];
                        $date = isset($date) ? $date : time();
                        echo Html::tag('span','<i class="far fa-fw fa-clock"></i>' . date('d-m-Y h:i',$date), ['class' => 'due-date']);
                        ?>
                    </h6>
                </div><!-- /.card-header -->
                <!-- .card-body -->
                <div class="card-body pt-0">
                    <!-- .list-group -->
                    <div class="list-group">
                        <? $comment = isset($valTask['comment']) ? strip_tags($valTask['comment']) : null; ?>
                        <?= $comment = strlen($comment) > 3 ? mb_substr(trim(strip_tags($comment)), 0, 94).'...' : $comment; ?>
                        <? $countOperations = isset($valTask['taskOperations']) ? count($valTask['taskOperations']) : 0 ; ?>

                        <!-- .list-group-item_kn -->
<!--                        <div class="list-group-item_kn" data-toggle="modal" data-target="#modalViewTask">-->
<!--                            <a href="#" class="stretched-link"></a> <!-- .list-group-item-body -->
<!--                            <div class="list-group-item-body">-->
<!--                                <!-- members -->
<!--                                <figure class="user-avatar" data-toggle="tooltip" title="" data-original-title="Johnny Day">-->
<!--                                    <img src="/assets/67b32868/images/avatars/uifaces2.jpg" alt="Johnny Day">-->
<!--                                </figure>-->
<!--                                <figure class="user-avatar" data-toggle="tooltip" title="" data-original-title="Sarah Bishop">-->
<!--                                    <img src="/assets/67b32868/images/avatars/uifaces3.jpg" alt="Sarah Bishop">-->
<!--                                </figure><!-- /members -->
<!--                            </div><!-- /.list-group-item-body -->
<!--                        </div><!-- /.list-group-item_kn -->
                        <!-- .list-group-item_kn -->
                        <div class="list-group-item_kn pt-0" data-toggle="modal" data-target="#modalViewTask">
                            <a href="#" class="stretched-link"></a> <!-- .list-group-item-body -->
                            <div class="list-group-item-body">
                                <div class="progress_kn progress-xs_kn">
                                    <div class="progress-bar <?= $color = isset($valTask['color']) ? $valTask['color'] : 'bg-success';?>" role="progressbar" style="width: 20%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div><!-- /.list-group-item-body -->
                            <!-- .list-group-item-figure -->
                            <div class="list-group-item-figure">
                                <span class="todos">19/<?= $countOperations?></span>
                            </div><!-- /.list-group-item-figure -->
                        </div>
                    </div><!-- /.list-group -->
                </div><!-- /.card-body -->
                <!-- .card-footer -->
                <div class="card-footer">
                    <?= Html::a("<i class=\"oi oi-comment-square mr-1\"></i> $countOperations", '#', ['data-url' => $editorURLTaskChange, "class" => "card-footer-item card-footer-item-bordered text-muted", "title" => \Yii::t($defaultTranslationCategory, "Operations history"), 'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"data-url\"));"]); ?>
                    <? if(!$fixedTaskStatus):?>
                    <? $archiveURLTaskActionChange = is_array($archiveURLTaskAction) ? Url::to(array_merge($archiveURLTaskAction,['id' => $taskID])) : $archiveURLTaskAction . "?id=$taskID";?>
                    <?= Html::a("<i class=\"fa fa-history text-teal_kn\"></i>", '#', ['data-url' => $editorURLTaskChange, "class" => "card-footer-item card-footer-item-bordered text-muted", "title" => \Yii::t($defaultTranslationCategory, "Set Reminder"), 'onclick' => "$(\"#$idModal\").modal(\"show\").find(\".modal-body\").load($(this).attr(\"data-url\"));"]); ?>
                    <?= Html::a("<i class=\"fa fa-archive text-red\"\"></i>", '#', ['data-url' => $editorURLTaskChange, "class" => "card-footer-item card-footer-item-bordered text-muted", "title" => \Yii::t($defaultTranslationCategory, "Transfer to archive"), 'onclick' => "$('#$removeModelID').attr('data-url','$archiveURLTaskActionChange').modal('show');"]); ?>
                    <?endif;?>
                </div><!-- /.card-footer -->
            </div><!-- .card -->
        </div>
    <?php endforeach; ?>

<?= Html::endTag('div');?>
<?= Html::endTag('div');?>
<?php endforeach; ?>
<?= Html::endTag('div');?>




