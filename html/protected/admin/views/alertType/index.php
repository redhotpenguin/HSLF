<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Alert Types',
    'brandUrl' => array('alertType/index'),
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
    'id' => 'alert-type-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'display_name',
        array(
            'header' => 'Tag',
            'name' => 'tag',
            'value' => '$data->tag->name',
        ),
        'category',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
