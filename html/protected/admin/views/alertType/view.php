<?php
$this->breadcrumbs = array(
    'Alert Types' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Add an alert type', 'url' => array('create')),
    array('label' => 'Update this alert type', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this alert type', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this alert type?')),
    array('label' => 'Manage alert types', 'url' => array('admin')),
);
?>

<h1>View: <?php echo $model->display_name; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'display_name',
        array(
            'name' => 'tag',
            'header' => 'Tag',
            'value' => $model->tag->name,
        ),
        'category',
    ),
));
?>
