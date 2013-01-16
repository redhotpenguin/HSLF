<?php
/* @var $this SharePayloadController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Share Payloads',
);

$this->menu = array(
    array('label' => 'Create a share payload', 'url' => array('create')),
    array('label' => 'Manage share payloads', 'url' => array('admin')),
);
?>

<h1>Share Payloads</h1>

<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'dataProvider' => $dataProvider,
    'dataProvider' => $dataProvider,
    'template' => "{pager}\n{items}\n{pager}", // pagination on top and on bottom

    'columns' => array(
        'id',
        'title',
        'email',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>
