<?php
$this->breadcrumbs = array(
    'Ballot Items',
);

$this->menu = array(
    array('label' => 'Add a ballot item', 'url' => array('create')),
    array('label' => 'Manage ballot items', 'url' => array('admin')),
    array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
    array('label' => 'Export Scorecards', 'url' => array('exportScorecardCSV')),
);
?>

<h1>Ballot Items</h1>

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
            'value' => '$data->district->state_abbr." ".$data->district->type." ".$data->district->number'
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