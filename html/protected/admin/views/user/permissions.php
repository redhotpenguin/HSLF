

<?php
echo CHtml::beginForm(array('user/updateTasks'), 'POST', array('id' => 'tasksForm'));


echo CHtml::hiddenField('tenantId', $tenantId);
echo CHtml::hiddenField('userId', $user->id);
?>

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
    $taskCount = count($tasks);
    $unassignedTasks = array_diff_key($tasks, $assignedTasks);

    foreach ($assignedTasks as $task):

        if ($i % 2 == 0) {
            echo '<tr>';
        }
        ?>

    <td> <?php echo $task->description; ?> </td>
    <td>  <?php echo CHtml::checkBox('tasks[]', true, array('value' => $task->name)); ?> </td>

    <?php
    if ($i % 2 == 1) {
        echo '</tr>';
    }

    $i++;
endforeach;

foreach ($unassignedTasks as $task):

    if ($i % 2 == 0) {
        echo '<tr>';
    }
    ?>

    <td> <?php echo $task->description; ?> </td>
    <td>  <?php echo CHtml::checkBox('tasks[]', false, array('value' => $task->name)); ?> </td>

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
<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));

echo CHtml::endForm(); // end form widget

if (getParam('result') == 'success') {
    echo '<div class="update_box btn-success">User permissions successfully updated</div>';
} elseif (getParam('result') == 'error') {
    echo '<div class="update_box btn-danger">Error while updating user permissions</div>';
}
?>
