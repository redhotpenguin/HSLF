

<?php
echo CHtml::beginForm(array('user/updateTask'), 'POST', array('id' => 'tasksForm'));
?>

<table class="table table-bordered table-striped">
    <?php
    foreach ($tasks as $task):
        echo '<tr>';
        ?>

        <td> <?php echo $task->description; ?> </td>
        <td> <?php echo CHtml::checkBox('tasks[]', true, array('value' => $task->name)); ?> </td>


        <?php
        echo '</tr>';
    endforeach;
    ?>

</table>
<div class="clearfix"></div>
<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));

echo CHtml::endForm(); // end form widget
?>
