<?php
$this->breadcrumbs = array(
    'Items' => array('index'),
    $model->item => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Create another item', 'url' => array('create')),
    array('label' => 'Delete this item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this ballot item?')),
    array('label' => 'Manage items', 'url' => array('admin')),
);
?>

<h1><?php echo $model->item; ?> #(<?php echo $model->id; ?>)</h1>

<?php
if (getParam('updated') == 1) {
    echo '<div class="success_update_box">Item updated</div>';
}


echo $this->renderPartial('_form', array(
    'model' => $model,
    'endorser_list' => $endorser_list,
        )
);
?>