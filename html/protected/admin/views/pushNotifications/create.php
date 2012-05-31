<?php
$this->breadcrumbs=array(
	'Push Notifications'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Push Notifications', 'url'=>array('admin')),
);
?>

<h1>Create A Push Notification </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>