<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List User_alert', 'url'=>array('index')),
	array('label'=>'Create User_alert', 'url'=>array('create')),
	array('label'=>'Update User_alert', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User_alert', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User_alert', 'url'=>array('admin')),
);
?>

<h1>View user alert #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'state_abbr',
		'district_id',
		'create_time',
	),
)); ?>
