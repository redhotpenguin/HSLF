<?php
$this->breadcrumbs=array(
	'User Alerts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List user alerts', 'url'=>array('index')),
	array('label'=>'Manage user alerts', 'url'=>array('admin')),
);
?>

<h1>Create an user alert</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>