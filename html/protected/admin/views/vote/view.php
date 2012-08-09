<?php
$this->breadcrumbs=array(
	'Votes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Add another vote option', 'url'=>array('create')),
	array('label'=>'Update', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage vote options', 'url'=>array('admin')),
);
?>

<h1>View Vote #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'icon',
	),
)); ?>
