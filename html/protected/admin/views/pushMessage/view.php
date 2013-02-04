<?php
$this->breadcrumbs=array(
	'Push Messages'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PushMessage','url'=>array('index')),
	array('label'=>'Create PushMessage','url'=>array('create')),
	array('label'=>'Update PushMessage','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete PushMessage','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PushMessage','url'=>array('admin')),
);
?>

<h1>View PushMessage #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tenant_id',
		'share_payload_id',
		'creation_date',
		'alert',
	),
)); ?>
