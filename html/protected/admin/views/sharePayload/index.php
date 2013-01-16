<?php
/* @var $this SharePayloadController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Share Payloads',
);

$this->menu=array(
	array('label'=>'Create SharePayload', 'url'=>array('create')),
	array('label'=>'Manage SharePayload', 'url'=>array('admin')),
);
?>

<h1>Share Payloads</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
