<?php
$this->breadcrumbs=array(
	'Scorecard Items',
);

$this->menu=array(
	array('label'=>'Add a scorecard item', 'url'=>array('create')),
	array('label'=>'Manage scorecard items', 'url'=>array('admin')),
);
?>

<h1>Scorecard Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
