<?php
$this->breadcrumbs=array(
	'Push Messages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PushMessage','url'=>array('index')),
	array('label'=>'Create PushMessage','url'=>array('create')),
	array('label'=>'View PushMessage','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage PushMessage','url'=>array('admin')),
);
?>

<h1>Update PushMessage <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>