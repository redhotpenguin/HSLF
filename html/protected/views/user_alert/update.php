<?php
$this->breadcrumbs = array(
    'User Alerts' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List user alerts', 'url' => array('index')),
    array('label' => 'Create user an alert', 'url' => array('create')),
    array('label' => 'View user alert', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Delete this user alert', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this alert?')),
    array('label' => 'Manage user alerts', 'url' => array('admin')),
);
?>

<h1>Update alert: <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>