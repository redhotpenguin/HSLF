<?php
$this->breadcrumbs=array(
	'Parties'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add another party', 'url'=>array('create')),
	array('label'=>'Manage parties', 'url'=>array('admin')),
);
?>

<h1>Update Party <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>