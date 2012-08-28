<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Create another ballot item', 'url' => array('create')),
    array('label' => 'Edit this ballot item', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this ballot item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this ballot item?')),
    array('label' => 'Manage ballot items', 'url' => array('admin')),
);
?>

<h1>View Ballot: <?php echo $model->item; ?></h1>

<?php
$state_name = $model->district->stateAbbr->name;
$district_type = $model->district->type;
$district_number = $model->district->number;


$district = $state_name . ' ' . $district_type . ' - ' . $district_number;
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'district_id',
        'item',
        'item_type',
        'recommendation_id',
        'next_election_date',
        'priority',
        'detail',
        'date_published',
        'published',
        array(
            'label' => 'Party',
            'value' => $model->party->name
        ),
        array(
            'label' => 'Office',
            'value' => $model->office->name
        ),
        'score',
        array(
            'name' => 'image_url',
            'label' => 'Image',
            'value' => "<a href='$model->image_url' target='_blank'> $model->image_url </a>",
            'type' => 'raw',
        ),
        'url',
        'election_result_id',
        array(
            'name' => 'district_id',
            'value' => $district
        ),
        'personal_url',
        'hold_office'
    ),
));
?>
