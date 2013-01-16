<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs=array(
	'Share Payloads'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create another share payload', 'url'=>array('create')),
	array('label'=>'Update this share payload', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete this share payload', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage share payloads', 'url'=>array('admin')),
);
?>

<h1>View Share Payload #<?php echo $model->id; ?></h1>

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
