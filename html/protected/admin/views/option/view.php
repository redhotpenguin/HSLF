<?php
$this->breadcrumbs = array(
    'Options' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'Add an option', 'url' => array('create')),
    array('label' => 'Update this option', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this option', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this option?')),
    array('label' => 'Manage options', 'url' => array('admin')),
);
?>

<h1>View Option #<?php echo $model->id; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'value',
    ),
));
?>
