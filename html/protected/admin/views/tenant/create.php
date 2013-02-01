<?php
/* @var $this TenantController */
/* @var $model Tenant */

$this->breadcrumbs=array(
	'Tenants'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage tenants', 'url'=>array('admin')),
);
?>

<h1>Add a tenant</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>