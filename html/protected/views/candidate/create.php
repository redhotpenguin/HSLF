<?php
$this->breadcrumbs=array(
	'Candidates'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Candidate', 'url'=>array('index')),
	array('label'=>'Manage Candidate', 'url'=>array('admin')),
);
?>

<h1>Create Candidate</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); 

?>
