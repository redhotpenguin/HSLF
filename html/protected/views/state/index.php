<?php
$this->breadcrumbs=array(
	'States',
);

$this->menu=array(
	array('label'=>'Create State', 'url'=>array('create')),
	array('label'=>'Manage State', 'url'=>array('admin')),
);
?>

<h1>States</h1>
<?php 

$dataProvider->pagination->pageSize = 60;

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'id',          
        'name',  
        'abbr',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));




?>
