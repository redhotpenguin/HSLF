<?php
$this->breadcrumbs=array(
	'Organizations'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add an organization', 'url'=>array('create')),
	array('label'=>'View organization', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage organizations', 'url'=>array('admin')),
);
?>

<h1>Update Organization: <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>