<?php
$this->breadcrumbs = array(
    'Endorsers',
);

$this->menu = array(
    array('label' => 'Add an endorser', 'url' => array('create')),
    array('label' => 'Manage endorsers', 'url' => array('admin')),
    array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
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
        array(
            'name' => 'website',
            'type'=>'raw',
            'value'=>' Chtml::link( $data->website, $data->website, array("target"=>"_blank")) '
        ),
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>
