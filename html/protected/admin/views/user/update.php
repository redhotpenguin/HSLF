<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Add another user', 'url' => array('create')),
    array('label' => 'Delete this user', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this user?')),
    array('label' => 'Manage users', 'url' => array('admin')),
);
?>

<h1>Update User: <?php echo $model->username; ?></h1>

<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">This user has been updated</div>';
}


$model->password = '';
echo $this->renderPartial('_update_form', array('model' => $model));
?>