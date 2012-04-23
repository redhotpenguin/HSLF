<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Option', 'url'=>array('index')),
	array('label'=>'Create Option', 'url'=>array('create')),
	array('label'=>'Update Option', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Option', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Option', 'url'=>array('admin')),
);
?>

<h1>View Option #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'value',
	),
)); ?>
