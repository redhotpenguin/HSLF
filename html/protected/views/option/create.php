<?php
$this->breadcrumbs=array(
	'Options'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage options', 'url'=>array('admin')),
);
?>

<h1>Add an option </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>