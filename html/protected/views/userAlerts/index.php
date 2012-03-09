<?php
$this->breadcrumbs=array(
	'User Alerts',
);

$this->menu=array(
	array('label'=>'Create a User Alert', 'url'=>array('create')),
	array('label'=>'Manage User Alerts', 'url'=>array('admin')),

);
?>

<h1>User Alerts</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'title',  
        'content',
        'state_abbr',
       
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));



?>
