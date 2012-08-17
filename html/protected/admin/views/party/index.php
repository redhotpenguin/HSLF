<?php
$this->breadcrumbs = array(
    'Parties',
);

$this->menu = array(
    array('label' => 'Add a party', 'url' => array('create')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);
?>

<h1>Parties</h1>


<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'party-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'abbr',
        'initial',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>

