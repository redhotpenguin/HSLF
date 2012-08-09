<?php
$this->breadcrumbs = array(
    'Scorecard Items' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'Add another scorecard item', 'url' => array('create')),
    array('label' => 'Update', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage scorecard items', 'url' => array('admin')),
);
?>

<h1>View Scorecard Item #<?php echo $model->id; ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'description',
        array(
            'label' => 'Office',
            'value' => $model->office->name
        ),
    ),
));
?>
