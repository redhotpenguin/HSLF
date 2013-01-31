<?php
/* @var $this TenantController */
/* @var $model Tenant */

$this->breadcrumbs=array(
	'Tenants'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add another tenant', 'url'=>array('create')),
	array('label'=>'Manage tenants', 'url'=>array('admin')),
);
?>

<h1>Update Tenant <?php echo $model->display_name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>