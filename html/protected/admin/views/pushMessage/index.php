<?php
$this->breadcrumbs=array(
	'Push Messages',
);

$this->menu=array(
	array('label'=>'Create a push message ','url'=>array('create')),
	array('label'=>'Manage push messages','url'=>array('admin')),
);
?>

<h1>Push Messages</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
