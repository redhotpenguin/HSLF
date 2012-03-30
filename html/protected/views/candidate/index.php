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
    'cssFile' => Yii::app()->baseUrl . '/css/gridview/styles.css',
    'columns'=>array(          
        'state_abbr',
        array(
            'header' => 'District',
            'value' => '$data->district->number'
        ),
        'type',
        'full_name',
        'party',
        'scorecard',
        'date_published',
        'publish',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
     
        
    ),
));

?>