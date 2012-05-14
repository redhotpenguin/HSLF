<?php
$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
	array('label'=>'Create a tag', 'url'=>array('create')),
	array('label'=>'Manage tags', 'url'=>array('admin')),
);
?>

<h1>Tags</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'type',
      
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
        ),
    ),
));


?>
