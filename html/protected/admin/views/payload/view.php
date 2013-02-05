<?php
/* @var $this SharePayloadController */
/* @var $model Payload */

$this->breadcrumbs=array(
	'Payloads'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create another payload', 'url'=>array('create')),
	array('label'=>'Update this payload', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete this payload', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage payloads', 'url'=>array('admin')),
);
?>

<h1>View Payload #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'url',
		'title',
		'description',
		'tweet',
		'email',
	),
)); ?>
