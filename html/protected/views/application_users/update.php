<?php
$this->breadcrumbs=array(
	'App Users'=>array('index'),
	$model->device_token=>array('view','id'=>$model->device_token),
	'Update',
);

$this->menu=array(
	array('label'=>'List AppUsers', 'url'=>array('index')),
	array('label'=>'Create AppUsers', 'url'=>array('create')),
	array('label'=>'View AppUsers', 'url'=>array('view', 'id'=>$model->device_token)),
	array('label'=>'Manage AppUsers', 'url'=>array('admin')),
);
?>

<h1>Update AppUsers <?php echo $model->device_token; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>