<?php
$this->breadcrumbs = array(
    'Offices',
);

$this->menu = array(
    array('label' => 'Create Office', 'url' => array('create')),
    array('label' => 'Manage Office', 'url' => array('admin')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);
?>

<h1>Offices</h1>


<?php

$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'office-grid',
    'dataProvider' => $dataProvider,
    
    'columns' => array(
        'id',
        'name',
       array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>

