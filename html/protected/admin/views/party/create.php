<?php
$this->breadcrumbs=array(
	'Parties'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage parties', 'url'=>array('admin')),
);
?>

<h1>Create Party</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>