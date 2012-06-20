<?php
$this->breadcrumbs=array(
	'Push Notifications'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
        array('label'=>'Manage Push Notifications', 'url'=>array('admin')),	
 
  	array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
        array('label'=>'Send', 'url'=>array('sendnotification', 'id'=>$model->id)),
);
?>

<h1>Update push notification <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>