<?php
$this->breadcrumbs = array(
    'Items',
);

$this->menu = array(
    array('label' => 'Add an item', 'url' => array('create')),
    array('label' => 'Manage items', 'url' => array('admin')),
    array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
    array('label' => 'Export Scorecards', 'url' => array('exportScorecardCSV')),
);
?>

<h1>Items</h1>

<?php
$dataProvider->pagination->pageSize = 50;
$this->widget('bootstrap.widgets.BootGridView', array(
    'dataProvider' => $dataProvider,
    'template' => "{pager}\n{items}\n{pager}", // pagination on top and on bottom

    'columns' => array(
        'id',
        'item',
        'item_type',
        array(
            'header' => 'District',
            'value' => '$data->district->display_name'
        ),
        'url',
        array(
            'header' => 'Election date',
            'value' => '$data->next_election_date',
        ),
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));