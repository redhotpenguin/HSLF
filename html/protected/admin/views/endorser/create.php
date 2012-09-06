<?php
$this->breadcrumbs=array(
	'Endorsers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage endorsers', 'url'=>array('admin')),
);
?>

<h1>Create Endorser</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>