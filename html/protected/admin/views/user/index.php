<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Add a user', 'url'=>array('create')),
	array('label'=>'Manage users', 'url'=>array('admin')),
);
?>

<h1>Users</h1>

<?php 


$template = '{view}{update}{delete}';


$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(        
        'username',  
        'email',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'bootstrap.widgets.TbButtonColumn',
             'template'=> $template,
        ),
    ),
));
?>
