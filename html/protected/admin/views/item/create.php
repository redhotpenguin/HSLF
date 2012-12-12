<?php
$this->breadcrumbs = array(
    'Items' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage items', 'url' => array('admin')),
);
?>

<h1>Add an item</h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'endorser_list' => $endorser_list,
));
?>