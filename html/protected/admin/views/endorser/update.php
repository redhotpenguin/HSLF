<?php
$this->breadcrumbs=array(
	'Endorsers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add an endorser', 'url'=>array('create')),
	array('label'=>'View endorser', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage endorsers', 'url'=>array('admin')),
);
?>

<h1>Update Endorser: <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>