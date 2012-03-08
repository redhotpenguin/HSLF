<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserAlerts', 'url'=>array('index')),
	array('label'=>'Create UserAlerts', 'url'=>array('create')),
	array('label'=>'View UserAlerts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserAlerts', 'url'=>array('admin')),
);
?>

<h1>Update UserAlerts <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>