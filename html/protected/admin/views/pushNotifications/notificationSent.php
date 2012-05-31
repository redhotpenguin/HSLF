<?php
$this->breadcrumbs=array(
	'Push Notifications'=>array('index'),
	'Confirmation',
);


$this->menu=array(
	array('label'=>'List Push Notifications', 'url'=>array('index')),
          array('label'=>'Create another Push Notification', 'url'=>array('create')),
	array('label'=>'Manage Push Notifications', 'url'=>array('admin')),
);


?>

<br>
<?php

echo $total.' push alerts sent!<br>';

?>
Thank you!<br>