<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Tags',
    'brandUrl' => array('tag/index'),
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
    'id' => 'tag-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'display_name',
        'type',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}'
        ),
    ),
));
