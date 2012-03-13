<?php
$this->breadcrumbs=array(
	'Districts',
);

$this->menu=array(
	array('label'=>'Create District', 'url'=>array('create')),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>Districts</h1>



<?php

$dataProvider->pagination->pageSize = 60;

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'number',  
        array(
            'name' => 'State',
            'value' => '$data->stateAbbr->name',
        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));


?>