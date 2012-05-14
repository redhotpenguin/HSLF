<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);



$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Add an user', 'url'=>array('create'), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
	array('label'=>'Update user', 'url'=>array('update', 'id'=>$model->id), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
	array('label'=>'Delete user', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this user?'), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
	array('label'=>'Manage users', 'url'=>array('admin'), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
);
?>

<h1>User: <?php echo $model->username; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'email',
	),
)); ?>
