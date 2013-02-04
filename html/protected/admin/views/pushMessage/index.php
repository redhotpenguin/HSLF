<?php
$this->breadcrumbs=array(
	'Push Messages',
);

$this->menu=array(
	array('label'=>'Create PushMessage','url'=>array('create')),
	array('label'=>'Manage PushMessage','url'=>array('admin')),
);
?>

<h1>Push Messages</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
