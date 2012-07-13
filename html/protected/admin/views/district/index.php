<?php
$this->breadcrumbs = array(
    'Districts',
);

$this->menu = array(
    array('label' => 'Add a district', 'url' => array('create')),
    array('label' => 'Manage districts', 'url' => array('admin')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);
?>

<h1>Districts</h1>



<?php
$dataProvider->pagination->pageSize = 60;

$this->widget('bootstrap.widgets.BootGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'State',
            'value' => '$data->stateAbbr->name',
        ),
        'type',
        'number',
        'display_name',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'deleteConfirmation' => "js:'Deleting this District will also delete every ballot items associated to it, continue?'",
        ),
    ),
));
?>