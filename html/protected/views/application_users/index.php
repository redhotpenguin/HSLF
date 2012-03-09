<?php
$this->breadcrumbs=array(
	'App Users',
);

$this->menu=array(
	array('label'=>'Create AppUsers', 'url'=>array('create')),
	array('label'=>'Manage AppUsers', 'url'=>array('admin')),
);
?>

<h1>Application Users</h1>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'device_token',  
        'state_abbr',
        'district_number',
        'type',
        'registration',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));



?>
