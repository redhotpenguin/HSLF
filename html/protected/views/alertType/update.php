<?php
$this->breadcrumbs=array(
	'Alert Types'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AlertType', 'url'=>array('index')),
	array('label'=>'Create AlertType', 'url'=>array('create')),
	array('label'=>'View AlertType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AlertType', 'url'=>array('admin')),
);
?>

<h1>Update AlertType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>