<?php

/* @var $this TenantController */
/* @var $model Tenant */

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Tenants',
    'brandUrl' => array('tenant/index'),
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
    'id' => 'tenant-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        'display_name',
        'email',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => "{update}{view}"
        ),
    ),
));

