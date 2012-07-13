<?php
$this->breadcrumbs = array(
    'Districts' => array('index'),
    $model->number,
);

$this->menu = array(
    array('label' => 'Add a district', 'url' => array('create')),
    array('label' => 'Update', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Deleting this District will also delete every ballot items associated to it, continue?')),
    array('label' => 'Manage districts', 'url' => array('admin')),
);
?>

<h1>View District: <?php echo $model->display_name; ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'state_abbr',
        'number',
        'type',
        'display_name',
    ),
));
?>
