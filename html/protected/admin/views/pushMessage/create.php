<?php
$this->breadcrumbs=array(
	'Push Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PushMessage','url'=>array('index')),
	array('label'=>'Manage PushMessage','url'=>array('admin')),
);
?>

<h1>Create PushMessage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>