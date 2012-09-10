<?php
$this->breadcrumbs=array(
	'Endorsers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Add an endorser', 'url'=>array('create')),
	array('label'=>'Update endorser', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete endorser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage endorsers', 'url'=>array('admin')),
);
?>

<h1>View Endorser #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'website',
		'image_url',
	),
)); ?>
