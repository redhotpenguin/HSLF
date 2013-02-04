<?php
$this->breadcrumbs=array(
	'States',
);

$this->menu=array(
	array('label'=>'Add a  state', 'url'=>array('create')),
	array('label'=>'Manage states', 'url'=>array('admin')),
        array('label' => 'Export to CSV', 'url' => array('exportCSV')),

);
?>

<h1>States</h1>
<?php 

$dataProvider->pagination->pageSize = 60;

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider'=>$dataProvider,
    'type'=>'striped bordered condensed',
    'columns'=>array(
        'id',          
        'name',  
        'abbr',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));




?>
