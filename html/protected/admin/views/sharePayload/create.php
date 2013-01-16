<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs=array(
	'Share Payloads'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SharePayload', 'url'=>array('index')),
	array('label'=>'Manage SharePayload', 'url'=>array('admin')),
);
?>

<h1>Create SharePayload</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>