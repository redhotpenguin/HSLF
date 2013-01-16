<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */

$this->breadcrumbs=array(
	'Share Payloads'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage share payloads', 'url'=>array('admin')),
);
?>

<h1>Create SharePayload</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>