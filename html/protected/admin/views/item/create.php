<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'Manage items', 'url' => array('admin')),
);
?>

<h1>Add a Ballot Item</h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'organization_list' => $organization_list,
));
?>