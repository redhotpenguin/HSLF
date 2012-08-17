<?php
$this->breadcrumbs=array(
	'Votes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage vote options', 'url'=>array('admin')),
);
?>

<h1>Add a vote option</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>