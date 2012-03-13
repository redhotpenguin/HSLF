<?php
$this->breadcrumbs=array(
	'User Alerts',
);

$this->menu=array(
	array('label'=>'Create an user alert', 'url'=>array('create')),
	array('label'=>'Manage user alerts', 'url'=>array('admin')),
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
        array(
            'name' => 'District',
            'value' => '$data->district->number'
        ),
        'create_time',
       
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));

?>
