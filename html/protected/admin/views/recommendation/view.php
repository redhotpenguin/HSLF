<?php
$this->breadcrumbs=array(
	'Recommendations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Add another recommendation', 'url'=>array('create')),
	array('label'=>'Update this recommendation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete this recommendation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage recommendations', 'url'=>array('admin')),
);
?>

<h1>View Recommendation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'value',
		'type',
	),
)); ?>
