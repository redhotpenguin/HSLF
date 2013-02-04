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

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'State',
            'value' => '$data->state->name',
        ),
        'type',
        'number',
        'display_name',
        'locality',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'deleteConfirmation' => "js:'Deleting this District will also delete every items associated to it, continue?'",
        ),
    ),
));
?>