<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Payloads',
    'brandUrl' => array('payload/index'),
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));


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
