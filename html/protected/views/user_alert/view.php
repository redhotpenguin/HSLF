<?php
$this->breadcrumbs = array(
    'User Alerts' => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => 'List user alerts', 'url' => array('index')),
    array('label' => 'Create an user alert', 'url' => array('create')),
    array('label' => 'Update this user alert', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this user alert', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this alert?')),
    array('label' => 'Manage user alerts', 'url' => array('admin')),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'title',
        'content',
        'state_abbr',
        array(
            'name' => 'district_id',
            'value' => $model->district->number,
        ),
        'create_time',
    ),
));
?>
