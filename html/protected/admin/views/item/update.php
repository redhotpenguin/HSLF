<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    $model->item => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Create another ballot item', 'url' => array('create')),
    array('label' => 'Delete this ballot item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage ballot items', 'url' => array('admin')),
);
?>

<h1><?php echo $model->item; ?></h1>

<?php
if (getParam('updated') == 1) {
    echo '<div class="success_update_box">Ballot Item updated</div>';
}


echo $this->renderPartial('_form', array(
    'model' => $model,
    'organization_list' => $organization_list,
        )
);
?>