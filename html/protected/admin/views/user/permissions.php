
<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/static/user/permissions.css');



//some droppable object (dropzone)
$this->beginWidget('zii.widgets.jui.CJuiDroppable', array(
    'options' => array(
        'drop' => 'js:function( event, ui ) {alert("Something drop on me!")}', //remember put js:
    ),
    'htmlOptions' => array(
        'class' => 'span12',
        'id' => 'currentPermissionsArea',
    )
));
?>

<h3>Current Permissions</h3>
<?php
if (count($assignedTasks) > 0):
    ?>

    <?php
    foreach ($assignedTasks as $task) {


        $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
            'htmlOptions' => array(
            ),
        ));
        ?>

        <div class="permission">
            <?php echo $task->name; ?>
        </div>
        <?php
        $this->endWidget();
    }
    ?>

    <?php
else:
    echo 'No permissions';
endif;

$this->endWidget();
?>

<h3>Available Permissions</h3>


<?php
foreach ($tasks as $task) {
    $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
        'htmlOptions' => array(
        ),
    ));
    ?>

    <div class="permission">
        <?php echo $task->name; ?>
    </div>
    <?php
    $this->endWidget();
}
 
 