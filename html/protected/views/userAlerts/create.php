<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserAlerts', 'url'=>array('index')),
	array('label'=>'Manage UserAlerts', 'url'=>array('admin')),
);
?>

<h1>Create UserAlerts</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>