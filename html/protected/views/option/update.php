<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List options', 'url'=>array('index')),
	array('label'=>'Add a new option', 'url'=>array('create')),
	array('label'=>'Manage options', 'url'=>array('admin')),
);
?>

<h1>Update option <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_update_form', array('model'=>$model)); ?>