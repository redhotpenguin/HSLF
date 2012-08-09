<?php
$this->breadcrumbs=array(
	'Scorecard Items',
);

$this->menu=array(
	array('label'=>'Add a scorecard item', 'url'=>array('create')),
	array('label'=>'Manage scorecard items', 'url'=>array('admin')),
        array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
);
?>

<h1>Scorecard Items</h1>

<?php

$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'scorecard-item-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'description',
        array(
            'header'=>'Office',
              'value' => '$data->office->name',
        ),
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>

