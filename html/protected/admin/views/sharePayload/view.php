<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs=array(
	'Share Payloads'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List SharePayload', 'url'=>array('index')),
	array('label'=>'Create SharePayload', 'url'=>array('create')),
	array('label'=>'Update SharePayload', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SharePayload', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SharePayload', 'url'=>array('admin')),
);
?>

<h1>View SharePayload #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tenant_id',
		'url',
		'title',
		'description',
		'tweet',
		'email',
	),
)); ?>
