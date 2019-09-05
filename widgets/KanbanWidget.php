<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 24.03.17
 * Time: 10:14
 */

namespace igor162\KanbanBoard\widgets;

use igor162\KanbanBoard\KanbanBoardAsset;

use kartik\icons\Icon;

use yii\base\InvalidConfigException;
use yii\base\Widget;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class KanbanWidget
 * @package igor162\KanbanBoard\widgets
 *
 * @property string $defaultTranslationCategory
 * @property string $showAddTask
 * @property boolean $editorHeaderStatus
 * @property string $idModal
 * @property string $nameBoard
 * @property array $options
 * @property array $cardFooterMenuItems
 * @property string $editorURLTaskStatus
 * @property string $archiveURLTaskAction
 * @property string $editorURLTask
 * @property string $changeURLTaskAction
 * @property string $addURLTask
 * @property string $taskMoving
 * @property string $taskItemID
 * @property string $data
 * @property array $dataTaskArray
 */
class KanbanWidget extends Widget
{
    const SHOW_ONE = 'showOneButton'; // Show the add button on the first model in the "Task Status"
    const SHOW_ALL = 'ShowAllButton'; // Show add button on all models in "Task Status"

    /** @var string Default labels translation category */
    private $defaultTranslationCategory = 'app.kanban';

    /**
     * Show add button in all status columns
     * @var string
     */
    public $showAddTask = self::SHOW_ONE;

    public $editorHeaderStatus = true;

    public $idModal = 'modalKanban';

    public $nameBoard = 'board';

    /**
     * Configuration and assignment of attributes to the main element of the plugin
     * @var array
     */
    public $options = [
        'class' => 'board',
        'data-toggle' => 'sortable',
        'data-draggable' => '.tasks',
        'data-handle' => '.task-header',
        'data-delay' => '100',
        'data-force-fallback' => 'true',
    ];

    /**
     * Task Button Options
     * @var array
     */
    public $cardFooterMenuItems = [];

    /**
     * Link to the model with the change "Task Statuses"
     * @var string
     */
    public $editorURLTaskStatus = true;
    /**
     * Link to a model with a change in "Tasks"
     * @var string
     */
    public $editorURLTask = true;
    /**
     * Link to the model with changing the sorting and the status of "Tasks"
     * @var string
     */
    public $changeURLTaskAction = true;
    /**
     * Link to the "Tasks" archiving operation
     * @var string
     */
    public $archiveURLTaskAction = true;
    /**
     * Link to the model with the addition of "Tasks"
     * @var string
     */
    public $addURLTask = true;

    /**
     * The name of the moveable task form
     * @var string
     */
    public $taskMoving = 'taskMoving_';
    /**
     * The name of the task
     * @var string
     */
    public $taskItemID = 'taskItemID_';

    /**
     * Model Data
     * @var string
     */
    public $data;

    /**
     * @var array
     */
    public $dataTaskArray = [];
    /**
     * @var \yii\base\Model the model that keeps the user-entered filter data. When this property is set,
     * the grid view will enable column-based filtering. Each data column by default will display a text field
     * at the top that users can fill in to filter the data.
     *
     * Note that in order to show an input field for filtering, a column must have its [[DataColumn::attribute]]
     * property set and the attribute should be active in the current scenario of $filterModel or have
     * [[DataColumn::filter]] set as the HTML code for the input field.
     *
     * When this property is not set (null) the filtering feature is disabled.
     */
    public $filterTask;

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        $this->options['id'] = $this->nameBoard;
        self::registerTranslations();
        parent::init();
    }

    public function run()
    {
        $this->registerAssets();
        $this->renderHtml();
    }

    private function registerTranslations()
    {
        \Yii::$app->i18n->translations[$this->defaultTranslationCategory] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vendor/igor162/KanbanBoard/widgets/messages',
        ];
    }

    protected function renderHtml()
    {

//        $directoryAsset = \Yii::$app->assetManager->getPublishedUrl('@vendor/igor162/KanbanBoard/assets');

        $data = Json::decode($this->data);

        if (!is_array($data['results'])) {
            throw new InvalidConfigException(\Yii::t($this->defaultTranslationCategory, "Data for model kanban are missing!"));
        }

        $data = $data['results'];
        $this->dataTaskArray = $data;

        if(count($data) === 0){
            throw new InvalidConfigException(\Yii::t($this->defaultTranslationCategory, "Data for model kanban are missing!"));
        }

        $this->registerJS();

        echo $this->render(
            'task-board',
            [
                'optionsBoard' => $this->options,
                'editorHeaderStatus' => $this->editorHeaderStatus,
                'showAddTask' => $this->showAddTask,
                'array' => $data,
                'editorURLTaskStatus' => $this->editorURLTaskStatus,
                'editorURLTask' => $this->editorURLTask,
                'addURLTask' => $this->addURLTask,
                'archiveURLTaskAction' => $this->archiveURLTaskAction,
                'idModal' => $this->idModal,
                'taskItemID' => $this->taskItemID,
                'defaultTranslationCategory' => $this->defaultTranslationCategory,
            ]
        );
    }

    /**
     * Register client assets
     */
    protected function registerJS()
    {
        $view = $this->getView();
        $dataTaskArray = $this->dataTaskArray;
        $count = count($dataTaskArray);
        $messageError = \Yii::t($this->defaultTranslationCategory, "Database save failed!"); // Error request to db
        $jsData = '';
        for($x=0; $x<$count; $x++) {
            if($dataTaskArray[$x]['fixed']) break; // Prevent the mouse from moving to the archive
            $name = $this->taskMoving . $x;
            $var = "var $name = document.getElementById('$name');";
            $changeURLTaskAction = is_array($this->changeURLTaskAction) ? Url::to($this->changeURLTaskAction) : $this->changeURLTaskAction; // Change Position Link



$jsData .= <<< JS

$var

new Sortable($name, {
    group: {
        name: 'task-issue',
        // pull: 'clone' // To clone: set pull to 'clone'
    },
    // chosenClass: "task-issuesss",
    animation: 150,
    store: {
        // Saving the acquired sorting (called each time upon sorting modification)
        set: function (sortable) {
              var order = sortable.toArray();
              badge_kn = sortable.el.parentElement.getElementsByClassName('badge_kn');
              badge_kn[0].innerText = '('+order.length+')';
                }
        },
  // Finished drag and drop Json request
     onEnd: function (evt) {
        
            var params = [];
            $('#'+evt.to.id+' .task-issue').each(function(i, e) {
                params[$(e).attr("data-value")] = i;
            });

         $.post('$changeURLTaskAction', {
            'taskItems':params,
            'statusID': $(evt.to).attr("data-value")
             },
              "json")
         .fail(function ( o, textStatus, errorThrown ) {
                // alert(o.responseText);
                alert("$messageError");
            });
    },
});

JS;

        }


$script = <<< JS
    // Select column under arm
    $(document).on("mouseenter mouseleave", ".task-header", function (e) {
        var t = "mouseenter" === e.type;
        $(this).parent().toggleClass("hover", t);
    });

$jsData

JS;

        $view->registerJs($script, \yii\web\View::POS_END);
    }

    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        AdminLooperAsset::register($view);
    }

}