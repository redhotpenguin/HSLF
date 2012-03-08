<?php
$this->breadcrumbs=array(
	'Candidates'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Candidate', 'url'=>array('index')),
	array('label'=>'Create Candidate', 'url'=>array('create')),
	array('label'=>'View Candidate', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Candidate', 'url'=>array('admin')),
);
?>

<h1>Update Candidate <?php echo $model->id; ?></h1>

<?php 

echo $this->renderPartial('_form', array('model'=>$model)); 


?>