<?php
$this->breadcrumbs=array(
	'Scorecard Items'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add another scorecard item', 'url'=>array('create')),
	array('label'=>'Manage scorecard items', 'url'=>array('admin')),
);
?>

<h1>Update Scorecard Item <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>