<?php
/* @var $this PayloadController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Payloads',
);

$this->menu = array(
    array('label' => 'Create a payload', 'url' => array('create')),
    array('label' => 'Manage payloads', 'url' => array('admin')),
);
?>

<h1>Payloads</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'dataProvider' => $dataProvider,
    'template' => "{pager}\n{items}\n{pager}", // pagination on top and on bottom

    'columns' => array(
        'id',
        'title',
        'email',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
