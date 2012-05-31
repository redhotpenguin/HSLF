<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create a tag', 'url'=>array('create')),
	array('label'=>'Manage tags', 'url'=>array('admin')),
);
?>

<h1>Update Tag '<?php echo $model->name; ?>'</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>