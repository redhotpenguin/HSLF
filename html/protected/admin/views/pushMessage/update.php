<?php
$this->breadcrumbs = array(
    'Push Messages' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Create a push message', 'url' => array('create')),
    array('label' => 'Manage push messages', 'url' => array('admin')),
);
?>

<h1>Update push message <?php echo $model->id; ?></h1>

<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Push Message Saved</div>';
}

echo $this->renderPartial('_form', array('model' => $model));
?>