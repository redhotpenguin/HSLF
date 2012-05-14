<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create a tag', 'url'=>array('create')),
	array('label'=>'Update tag', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete tag', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this tag?')),
	array('label'=>'Manage tags', 'url'=>array('admin')),
);
?>

<h1>View Tag #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'type',
	),
)); ?>
