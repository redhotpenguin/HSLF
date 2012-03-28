<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create'),  'visible'=>  isset($user->role) && ($user->role==="admin")),
	array('label'=>'Manage User', 'url'=>array('admin') ),
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
