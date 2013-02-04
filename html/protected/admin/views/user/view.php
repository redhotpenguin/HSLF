<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->id,
);



$this->menu = array(
    array('label' => 'Add another user', 'url' => array('create')),
    array('label' => 'Update this user', 'url' => array('update', 'id' => $model->id) ),
    array('label' => 'Delete this user', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this user?')),
    array('label' => 'Manage users', 'url' => array('admin')),
);
?>

<h1>User: <?php echo $model->username; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'username',
        'email',
    ),
));
?>
