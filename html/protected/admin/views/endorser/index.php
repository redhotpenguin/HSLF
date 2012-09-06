<?php
$this->breadcrumbs = array(
    'Endorsers',
);

$this->menu = array(
    array('label' => 'Add an endorser', 'url' => array('create')),
    array('label' => 'Manage endorsers', 'url' => array('admin')),
);
?>

<h1>Endorsers</h1>

<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'vote-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'website',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>
