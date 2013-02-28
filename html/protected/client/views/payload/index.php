<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Payloads';
$this->secondaryNav['url'] = array('payload/index');


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => '-payload-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'nullDisplay' => '<em>NOT SET</em>',
    'columns' => array(
        'id',
        'title',
        'url',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
?>
