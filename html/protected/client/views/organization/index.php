<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Organizations',
    'brandUrl' => array('organization/index'),
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
    'id' => 'vote-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'display_name',
        array(
            'name' => 'website',
            'type' => 'raw',
            'value' => ' Chtml::link( $data->website, $data->website, array("target"=>"_blank")) '
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));