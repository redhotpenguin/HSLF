<?php
$this->breadcrumbs = array(
    'Organizations' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'Add an organization', 'url' => array('create')),
    array('label' => 'Update organization', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete organization', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage organization', 'url' => array('admin')),
);
?>

<h1>View Organization #<?php echo $model->id; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'display_name',
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
        array(
            'name' => 'facebook_url',
            'type' => 'raw',
            'value' => CHtml::link($model->facebook_url, $model->facebook_url, array('target' => '_blank')),
        ),
        array(
            'name' => 'twitter_handle',
            'type' => 'raw',
            'value' => CHtml::link($model->twitter_handle, 'http://www.twitter.com/'.$model->twitter_handle, array('target' => '_blank')),
        ),
    ),
));


if ($model->image_url)
    echo "<img src='{$model->image_url}' />";
?>


