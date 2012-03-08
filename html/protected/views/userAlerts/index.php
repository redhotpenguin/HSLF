<?php
$this->breadcrumbs=array(
	'User Alerts',
);

$this->menu=array(
	array('label'=>'Create a User Alert', 'url'=>array('create')),
	array('label'=>'Manage User Alerts', 'url'=>array('admin')),

);
?>

<h1>User Alerts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
