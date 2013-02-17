<?php

/* @var $this TenantController */
/* @var $model Tenant */

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Tenants';
$this->secondaryNav['url'] =array('tenant/index');


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

