<?php
$this->breadcrumbs = array(
    'Votes',
);

$this->menu = array(
    array('label' => 'Add a new vote option', 'url' => array('create')),
    array('label' => 'Manage vote options', 'url' => array('admin')),
);
?>

<h1>Votes</h1>


<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'vote-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'icon',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>