<?php
$this->breadcrumbs = array(
    'Candidates' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'Add another candidate', 'url' => array('create')),
    array('label' => 'Manage other candidates', 'url' => array('admin')),
    array('label' => 'Update this candidate', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete this candidate', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this candidate?')),
);
?>

<h1><?php echo $model->full_name; ?></h1>



<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'name' => 'district_id',
            'value' => $model->district->number,
        ),
        'type',
        array(
            'name' => 'endorsement',
            'header' => 'endorsement',
            'value' => substr(strip_tags($model->endorsement), 0, 300) . '...',
        ),
        'full_name',
        'party',
        'date_published',
        'publish',
        'scorecard',
        array(
            'type'=>'html',
            'header'=>'Issues',
            'name'=>'issues',
            'value'=>$model->getHTMLIssues(),
        ),
    ),
));

?>

