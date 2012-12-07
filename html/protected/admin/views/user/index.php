<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Add a user', 'url'=>array('create'), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
	array('label'=>'Manage users', 'url'=>array('admin'), 'visible'=>Yii::app()->user->getState('role') == 'admin'),
);
?>

<h1>Users</h1>

<?php 

if(isAdmin())
    $template = '{view}{update}{delete}';
else
    $template = '{view}';

$this->widget('bootstrap.widgets.BootGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'username',  
        'email',
        'role',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'bootstrap.widgets.BootButtonColumn',
             'template'=> $template,
        ),
    ),
));
?>
