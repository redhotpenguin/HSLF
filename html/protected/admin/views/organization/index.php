<?php
$this->breadcrumbs = array(
    'Organizations',
);

$this->menu = array(
    array('label' => 'Add an organization', 'url' => array('create')),
    array('label' => 'Manage organizations', 'url' => array('admin')),
    array('label' => 'Export to CSV file', 'url' => array('exportCSV')),
);
?>

<h1>Organizations</h1>

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
