<?php
$this->breadcrumbs=array(
	'Offices',
);

$this->menu=array(
	array('label'=>'Create Office', 'url'=>array('create')),
	array('label'=>'Manage Office', 'url'=>array('admin')),
);
?>

<h1>Offices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
