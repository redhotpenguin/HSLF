<?php
$this->breadcrumbs=array(
	'Districts'=>array('index'),
	$model->number,
);

$this->menu=array(
	array('label'=>'List District', 'url'=>array('index')),
	array('label'=>'Create District', 'url'=>array('create')),
	array('label'=>'Update District', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete District', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>View District #<?php echo $model->number; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'state_abbr',
		'number',
	),
)); ?>
