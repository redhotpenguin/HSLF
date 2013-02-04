<?php
/* @var $this TenantController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Tenants',
);

$this->menu = array(
    array('label' => 'Add a tenant', 'url' => array('create')),
    array('label' => 'Manage tenants', 'url' => array('admin')),
);
?>

<h1>Tenants</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'display_name',
        'email',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => "{update}{view}"
        ),
    ),
));
