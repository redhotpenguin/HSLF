<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add a user', 'url'=>array('create')),
	array('label'=>'Manage users', 'url'=>array('admin')),
);
?>

<h1>Update User: <?php echo $model->username; ?></h1>

<?php 
$model->password='';
echo $this->renderPartial('_update_form', array('model'=>$model));
?>