<?php
$this->breadcrumbs=array(
	'App Users'=>array('index'),
	$model->device_token,
);

$this->menu=array(
	array('label'=>'List AppUsers', 'url'=>array('index')),
	array('label'=>'Create AppUsers', 'url'=>array('create')),
	array('label'=>'Update AppUsers', 'url'=>array('update', 'id'=>$model->device_token)),
	array('label'=>'Delete AppUsers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->device_token),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AppUsers', 'url'=>array('admin')),
);
?>

<h1>View AppUsers #<?php echo $model->device_token; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'device_token',
		'latitude',
		'longitude',
		'state_abbr',
		'district_number',
		'registration',
                'type',
		'user_agent',
	),
)); ?>
