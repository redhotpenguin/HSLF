<?php
$this->breadcrumbs = array(
    'Districts' => array('index'),
    $model->display_name => array('view', 'id' => $model->number),
    'Update',
);

$this->menu = array(
    array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Deleting this District will also delete every ballot items associated to it, continue?')),
    array('label' => 'Add a district', 'url' => array('create')),
    array('label' => 'Manage districts', 'url' => array('admin')),
);
?>

<h1>Update District <?php echo $model->display_name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>