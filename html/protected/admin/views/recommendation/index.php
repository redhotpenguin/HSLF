<?php
$this->breadcrumbs = array(
    'Recommendations',
);

$this->menu = array(
    array('label' => 'Add a recommendation', 'url' => array('create')),
    array('label' => 'Manage recommendations', 'url' => array('admin')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);
?>

<h1>Recommendations</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'type',
        'value',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
