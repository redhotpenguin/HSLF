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

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'username',  
        'email',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));
?>
