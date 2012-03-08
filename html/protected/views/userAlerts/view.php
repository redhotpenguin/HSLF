<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List User Alerts', 'url'=>array('index')),
	array('label'=>'Create a User Alert', 'url'=>array('create')),
	array('label'=>'Update a User Alert', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete a User Alert', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User Alerts', 'url'=>array('admin')),
        array('label'=>'Send a User Alert', 'url'=>array('send',  'id'=>$model->id) ),
);
?>

<h1>View User Alerts #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'state_abbr',
		'district_number',
	),
)); ?>
