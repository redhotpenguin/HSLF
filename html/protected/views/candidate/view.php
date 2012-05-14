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

<div id="candidate_preview">
    <div id="iphone_bg">
        <div id="Candidate_full_name_preview" class="input_preview"><?php echo $model->full_name; ?></div>
        <div id="Candidate_state_abbr_preview" class="list_preview"><?php echo $model->state_abbr; ?></div>
        <div id="Candidate_district_id_preview" class="list_preview"><?php echo $model->district->number; ?></div>
        <div id="Candidate_type_preview" class="list_preview"><?php echo $model->type; ?></div>
        <div id="Candidate_party_preview" class="list_preview"><?php echo $model->party; ?></div>
        <div id="Candidate_scorecard_preview" class="input_preview"><?php echo $model->scorecard; ?></div>
        <div id="Candidate_date_published_preview" class="input_preview"><?php echo $model->date_published; ?></div>
        <div id="Candidate_endorsement_preview" class="input_preview"><?php echo $model->endorsement; ?></div>
    </div>
</div>


<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'state_abbr',
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

