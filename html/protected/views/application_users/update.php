<?php
$this->breadcrumbs=array(
	'Application Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List application users', 'url'=>array('index')),
	array('label'=>'Create an application user', 'url'=>array('create')),
	array('label'=>'View application users', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage application users', 'url'=>array('admin')),
);
?>

<h1>Updat application user:<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>