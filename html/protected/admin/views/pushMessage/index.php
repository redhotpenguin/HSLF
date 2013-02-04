<?php
$this->breadcrumbs = array(
    'Push Messages',
);

$this->menu = array(
    array('label' => 'Create a push message ', 'url' => array('create')),
    array('label' => 'Manage push messages', 'url' => array('admin')),
);
?>

<h1>Push Messages</h1>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'push-message-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        array(
            'name' => 'alert',
            'value' => 'substr($data->alert, 0, 30)."...";'
        ),
        'creation_date',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));