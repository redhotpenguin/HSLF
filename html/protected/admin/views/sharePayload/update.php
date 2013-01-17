<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs = array(
    'Share Payloads' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Add another share payload', 'url' => array('create')),
    array('label' => 'Delete this share payload', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage share payloads', 'url' => array('admin')),
);
?>

<h1>Update Share Payload #<?php echo $model->id; ?></h1>

<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Share Payload Saved</div>';
}

echo $this->renderPartial('_form', array('model' => $model));
