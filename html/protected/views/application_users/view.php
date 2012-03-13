<?php
$this->breadcrumbs=array(
	'Application Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Application_users', 'url'=>array('index')),
	array('label'=>'Create Application_users', 'url'=>array('create')),
	array('label'=>'Update Application_users', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Application_users', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Application_users', 'url'=>array('admin')),
);
?>

<h1>View application user #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'device_token',
		'latitude',
		'longitude',
		'state_abbr',
		'district',
		'registration',
		'type',
		'user_agent',
	),
)); ?>
