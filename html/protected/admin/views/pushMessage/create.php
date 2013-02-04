<?php
$this->breadcrumbs=array(
	'Push Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage push messages','url'=>array('admin')),
);
?>

<h1>Create a push message </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>