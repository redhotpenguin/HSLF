<?php
$this->breadcrumbs = array(
    'Options',
);

$this->menu = array(
    array('label' => 'Add an option', 'url' => array('create')),
    array('label' => 'Manage options', 'url' => array('admin')),
    array('label' => 'File Editor', 'url' => array('editor')),
);
?>

<h1>Options</h1>

<?php
if (isAdmin())
    $template = '{view}{update}{delete}';
else
    $template = '{view}{update}';


$dataProvider->pagination->pageSize=50;

$this->widget('bootstrap.widgets.BootGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'name',
        array(
            'name'=>'value',
            'value'=>'substr($data->value,0,100);'
        ),
        
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'template' => $template,
        ),
    ),
));
