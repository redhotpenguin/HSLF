<?php
$this->breadcrumbs=array(
	'Recommendations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage recommendations', 'url'=>array('admin')),
);
?>

<h1>Add a recommendation</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>