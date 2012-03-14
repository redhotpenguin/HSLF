<?php
$this->breadcrumbs=array(
	'Candidates',
);

$this->menu=array(
	array('label'=>'Create Candidate', 'url'=>array('create')),
	array('label'=>'Manage Candidate', 'url'=>array('admin')),
);
?>

<h1>Candidates</h1>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(          
        'state_abbr',
        array(
            'header' => 'District',
            'value' => '$data->district->number'
        ),
        'type',
        'full_name',
        'party',
         array(
            'header' => 'endorsement',
            'value' => 'substr( $data->endorsement, 0, 300 )."..."',
        ),
        'scorecard',
        'date_published',
        'publish',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));

?>