<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this option?')),
	array('label'=>'Manage options', 'url'=>array('admin')),
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
