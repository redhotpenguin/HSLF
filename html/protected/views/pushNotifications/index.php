<?php
$this->breadcrumbs=array(
	'Push Notifications',
);

$this->menu=array(
	array('label'=>'Create a Push Notification', 'url'=>array('create')),
	array('label'=>'Manage Push Notification', 'url'=>array('admin')),
);
?>

<h1>Push Notifications</h1>

<?php
$dataProvider->pagination->pageSize = 50;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'message',  
        'sent',
        'create_time',
       
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));


?>
