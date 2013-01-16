<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs=array(
	'Share Payloads'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SharePayload', 'url'=>array('index')),
	array('label'=>'Create SharePayload', 'url'=>array('create')),
	array('label'=>'View SharePayload', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SharePayload', 'url'=>array('admin')),
);
?>

<h1>Update SharePayload <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>