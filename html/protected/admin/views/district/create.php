<?php
$this->breadcrumbs=array(
	'Districts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage districts', 'url'=>array('admin')),
);
?>

<h1>Add a district</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); 





?>


