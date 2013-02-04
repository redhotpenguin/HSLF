<?php
$this->breadcrumbs = array(
    'Votes',
);

$this->menu = array(
    array('label' => 'Add another vote option', 'url' => array('create')),
    array('label' => 'Manage vote options', 'url' => array('admin')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);
?>

<h1>Votes</h1>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'vote-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'icon',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>