<?php
$this->breadcrumbs=array(
	'Options',
);

$this->menu=array(
	array('label'=>'Add an option', 'url'=>array('create')),
	array('label'=>'Manage options', 'url'=>array('admin')),
);
?>

<h1>Options</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'id',          
        'name',  
        'value',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));