<?php
$this->breadcrumbs=array(
	'Application Users',
);

$this->menu=array(
	array('label'=>'Create an application user', 'url'=>array('create')),
	array('label'=>'Manage application users', 'url'=>array('admin')),
);
?>

<h1>Application Users</h1>

<?php 


$dataProvider->pagination->pageSize = 50;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'device_token',  
        'state_abbr',
        array(
            'name' => 'district',
            'value' => '$data->district0->number',
          ),
        'type',
        'registration',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));

?>
