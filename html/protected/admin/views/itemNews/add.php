<?php

$this->breadcrumbs = array(
    'Items' => array('item/index'),
    $model->item->item => array('item/update', 'id' => $model->item->id),
    'Add news update',
);

?>

<h1>Add an item news for: <?php echo $model->item->item; ?> </h1>

<?php

echo $this->renderPartial('_form', array('model' => $model  ));
?>