<?php
$this->breadcrumbs=array(
	'Push Notifications'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PushNotifications', 'url'=>array('index')),
	array('label'=>'Create PushNotifications', 'url'=>array('create')),
	array('label'=>'View PushNotifications', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PushNotifications', 'url'=>array('admin')),
);
?>

<h1>Update PushNotifications <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>