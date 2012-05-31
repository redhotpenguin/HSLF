<?php
$this->breadcrumbs=array(
	'Recommendations',
);

$this->menu=array(
	array('label'=>'Add a recommendation', 'url'=>array('create')),
	array('label'=>'Manage recommendations', 'url'=>array('admin')),
);
?>

<h1>Recommendations</h1>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'type',
        'value',
    ),
));

?>
