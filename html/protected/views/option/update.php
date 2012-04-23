<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Option', 'url'=>array('index')),
	array('label'=>'Create Option', 'url'=>array('create')),
	array('label'=>'View Option', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Option', 'url'=>array('admin')),
);
?>

<h1>Update Option <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>