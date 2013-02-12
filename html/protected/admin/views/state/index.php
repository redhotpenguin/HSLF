<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'States',
    'brandUrl' => array('state/index'),
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
    'id' => 'state-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'abbr',
        'name',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'deleteConfirmation' => "js:'Deleting this State will also delete every districts and  items associated to it, continue?'",
            'template' => '{update}{delete}'
        ),
    ),
));
?>
