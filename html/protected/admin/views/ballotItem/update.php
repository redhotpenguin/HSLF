<?php
$this->breadcrumbs=array(
	'Ballot Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create another ballot item', 'url'=>array('create')),
	array('label'=>'Manage ballot items', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->item; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>