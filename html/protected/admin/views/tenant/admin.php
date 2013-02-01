<?php
/* @var $this TenantController */
/* @var $model Tenant */

$this->breadcrumbs = array(
    'Tenants' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Add a tenant', 'url' => array('create')),
);
?>

<h1>Manage Tenants</h1>


<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'tenant-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        'display_name',
        'email',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'template' => "{update}{view}"
        ),
    ),
));

