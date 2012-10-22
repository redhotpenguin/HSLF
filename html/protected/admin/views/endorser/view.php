<?php
$this->breadcrumbs = array(
    'Endorsers' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'Add an endorser', 'url' => array('create')),
    array('label' => 'Update endorser', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete endorser', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage endorsers', 'url' => array('admin')),
);
?>

<h1>View Endorser #<?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'display_name',
        'list_name',
        'description',
        'slug',
        array(
            'name' => 'website',
            'type' => 'raw',
            'value' => CHtml::link($model->website, $model->website, array('target' => '_blank')),
        ),
        array(
            'name' => 'image_url',
            'type' => 'raw',
            'value' => CHtml::link($model->image_url, $model->image_url, array('target' => '_blank')),
        ),
        'facebook_share',
        'twitter_share'
    ),
));


if ($model->image_url)
    echo "<img src='{$model->image_url}' />";
?>


