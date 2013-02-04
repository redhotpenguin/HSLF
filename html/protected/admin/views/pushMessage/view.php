<?php
$this->breadcrumbs = array(
    'Push Messages' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Update', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Create a push message', 'url' => array('create')),
    array('label' => 'Manage push messages', 'url' => array('admin')),
);
?>

<h1>View push message  #<?php echo $model->id; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'share_payload_id',
        'creation_date',
        array(
            'name' => 'alert',
            'value' => substr($model->alert, 0, 30)."..."
        ),
    ),
));
?>
