<?php
$this->breadcrumbs=array(
	'Alert Types',
);

$this->menu=array(
	array('label'=>'Add an alert type', 'url'=>array('create')),
	array('label'=>'Manage alert types', 'url'=>array('admin')),
);
?>

<h1>Alert Types</h1>

<?php 

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'display_name',
        array(
            'name' => 'Tag',
            'value' => '$data->tag->name',
        ),
        'category',

        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));




?>
