<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User_alert', 'url'=>array('index')),
	array('label'=>'Create User_alert', 'url'=>array('create')),
	array('label'=>'View User_alert', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage User_alert', 'url'=>array('admin')),
);
?>

<h1>Update alert: <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>