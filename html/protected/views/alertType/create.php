<?php
$this->breadcrumbs=array(
	'Alert Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage alert types', 'url'=>array('admin')),
);
?>

<h1>Add an alert type</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>