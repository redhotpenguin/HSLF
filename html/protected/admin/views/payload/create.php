<?php
/* @var $this SharePayloadController */
/* @var $model Payload */

$this->breadcrumbs=array(
	'Payloads'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage payloads', 'url'=>array('admin')),
);
?>

<h1>Create a Payload:</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>