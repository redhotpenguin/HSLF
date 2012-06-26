<?php
$this->breadcrumbs = array(
    'Ballot Items',
);

$this->menu = array(
    array('label' => 'Add a ballot item', 'url' => array('create')),
    array('label' => 'Manage ballot items', 'url' => array('admin')),
    array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
);
?>

<h1>Ballot Items</h1>

<?php
$dataProvider->pagination->pageSize = 50;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'item',
        'item_type',
        array(
            'header' => 'District',
            'value' => '$data->district->state_abbr." ".$data->district->type." ".$data->district->number'
        ),
        'url',
        array(
            'header' => 'Election date',
            'value' => '$data->next_election_date',
        ),
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
        ),
    ),
));