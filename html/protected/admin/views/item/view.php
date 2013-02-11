<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    $model->item,
);

$this->menu = array(
    array('label' => 'Create another ballot item', 'url' => array('create')),
    array('label' => 'Edit this ballot item', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this ballot item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage ballot items', 'url' => array('admin')),
);
?>

<?php
$state_name = $model->district->state->name;
$district_type = $model->district->type;
$district_number = $model->district->number;


$district = $state_name . ' ' . $district_type . ' - ' . $district_number;
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'label' => 'Item',
            'value' => $model->item
        ),
        array(
            'label' => 'First name',
            'value' => $model->first_name
        ),
             array(
            'label' => 'Last name',
            'value' => $model->last_name
        ),
        array(
            'label' => 'Type',
            'value' => $model->item_type
        ),
        array(
            'label' => 'Recommendation',
            'value' => $model->recommendation->value
        ),
        'next_election_date',
        array(
            'name' => 'district_id',
            'value' => $district
        ),
        array(
            'name' => 'Detail',
            'value' => substr(strip_tags($model->detail), 0, 100) . '...',
        ),
        array(
            'label' => 'Party',
            'value' => $model->party->name
        ),
        array(
            'name' => 'image_url',
            'label' => 'Image',
            'value' => "<a href='$model->image_url' target='_blank'> $model->image_url </a>",
            'type' => 'raw',
        ),
        'slug',
        array(
            'name' => 'website',
            'type' => 'raw',
            'value' => CHtml::link($model->website, $model->website, array('target' => '_blank')),
        ),
        array(
            'name' => 'facebook_url',
            'type' => 'raw',
            'value' => CHtml::link($model->facebook_url, $model->facebook_url, array('target' => '_blank')),
        ),
        array(
            'name' => 'twitter_handle',
            'type' => 'raw',
            'value' => CHtml::link($model->twitter_handle, "https://twitter.com/" . $model->twitter_handle, array('target' => '_blank')),
        ),
        'friendly_name',
        'measure_number',
        'published',
        'date_published',
    ),
));
?>
