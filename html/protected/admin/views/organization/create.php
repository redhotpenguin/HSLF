<?php
$this->breadcrumbs=array(
	'Organizations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage organizations', 'url'=>array('admin')),
);
?>

<h1>Add an Organization</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>