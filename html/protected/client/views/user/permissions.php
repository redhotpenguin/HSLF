<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/user/user.js');

$navBarItems = array();


array_push($navBarItems, '', array('label' => "$user->username ({$tenant->display_name})", 'url' => array('update', 'id' => $user->id),
));

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Users';
$this->secondaryNav['url'] = array('user/index');

echo CHtml::beginForm(array('user/updateTasks'), 'POST', array('id' => 'tasksForm'));


echo CHtml::hiddenField('tenantId', $tenant->id);
echo CHtml::hiddenField('userId', $user->id);
?>

<div class="form">

    <table class="table table-bordered table-striped">
        <thead>
        <th>Permission</th>
        <th>Active</th>
        <th>Permission</th>
        <th>Active</th>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $taskCount = count($taskList);

            foreach ($taskList as $taskName => $taskValue):

                if ($i % 2 == 0) {
                    echo '<tr>';
                }
                ?>

            <td> <?php echo $taskValue['description']; ?> </td>
            <td>  <?php echo CHtml::checkBox('tasks[]', $taskValue['checked'], array('value' => $taskName)); ?> </td>

            <?php
            if ($i % 2 == 1) {
                echo '</tr>';
            }

            $i++;
        endforeach;
        ?>
        </tbody>

    </table>
    <div class="clearfix"></div>

    <div class="row-fluid">
        <p class="btn" id="select_all">select all</p> <p class="btn" id="deselect_all">deselect all</p>

    </div>

    <br/>
    <div class="row-fluid">
        <div class="span6">
            <?php
            echo CHtml::label('Project administrator:', 'projectAdministrator');
            echo CHtml::checkBox('projectAdministrator', $projectAdministrator);
            ?>
        </div>
    </div>

    <br/>

    <div class="row-fluid">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));
        ?>
    </div>
</div>

<?php
echo CHtml::endForm(); // end form widget

if (getParam('result') == 'success') {
    echo '<div class="update_box btn-success">User permissions successfully updated</div>';
} elseif (getParam('result') == 'error') {
    echo '<div class="update_box btn-danger">Error while updating user permissions</div>';
}
?>

