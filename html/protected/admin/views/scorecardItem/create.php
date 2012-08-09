<?php
$this->breadcrumbs=array(
	'Scorecard Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage scorecard items', 'url'=>array('admin')),
);
?>

<h1>Add a Scorecard Item</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>