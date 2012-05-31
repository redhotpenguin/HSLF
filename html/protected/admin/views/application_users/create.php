<?php
$this->breadcrumbs=array(
	'Application Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List application users', 'url'=>array('index')),
	array('label'=>'Manage application users', 'url'=>array('admin')),
);
?>

<h1>Create an application user:</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>