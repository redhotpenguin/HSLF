<?php
$this->breadcrumbs = array(
    'Candidates',
);

$this->menu = array(
    array('label' => 'Add a candidate', 'url' => array('create')),
    array('label' => 'Manage candidates', 'url' => array('admin')),
);
?>

<h1>Candidates</h1>


<?php
$dataProvider->pagination->pageSize = 50;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'header' => 'State',
            'value' => '$data->district->state_abbr'
        ),
        array(
            'header' => 'District',
            'value' => '$data->district->number'
        ),
        'type',
        'full_name',
        'party',
        'scorecard',
        'date_published',
        'publish',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
        ),
    ),
));
?>