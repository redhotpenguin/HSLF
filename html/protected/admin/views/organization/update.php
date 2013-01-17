<?php
$this->breadcrumbs = array(
    'Organizations' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Add an organization', 'url' => array('create')),
    array('label' => 'Delete this organization', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'View organization', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage organizations', 'url' => array('admin')),
);
?>

<h1>Update Organization: <?php echo $model->name; ?></h1>

<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Organization Saved</div>';
}
echo $this->renderPartial('_form', array('model' => $model));
?>