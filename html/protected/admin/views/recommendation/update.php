<?php
$this->breadcrumbs=array(
	'Recommendations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add another recommendation', 'url'=>array('create')),
    	array('label'=>'Delete this recommendation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage recommendations', 'url'=>array('admin')),
);
?>

<h1>Update Recommendation <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>