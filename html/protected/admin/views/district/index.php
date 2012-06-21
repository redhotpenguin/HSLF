<?php
$this->breadcrumbs = array(
    'Districts',
);

$this->menu = array(
    array('label' => 'Add a district', 'url' => array('create')),
    array('label' => 'Manage districts', 'url' => array('admin')),
);
?>

<h1>Districts</h1>



<?php
$dataProvider->pagination->pageSize = 60;

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'State',
            'value' => '$data->stateAbbr->name',
        ),
        'type',
        'number',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
             'deleteConfirmation'=>"js:'Deleting this District will also delete every ballot items associated to it, continue?'",

        ),
    ),
));
?>