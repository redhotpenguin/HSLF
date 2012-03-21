<?php
$this->breadcrumbs=array(
	'Push Notifications'=>array('index'),
	'Send',
);


?>

<h1>Send Notification</h1>

<?php 
    echo $this->renderPartial('_sendform', array('model'=>$model, 'data'=>$data)); 
 ?>
  
