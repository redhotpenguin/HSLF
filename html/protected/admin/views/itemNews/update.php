<?php
$this->breadcrumbs = array(
    'Items' => array('item/index'),
    $model->item->item => array('item/update', 'id' => $model->item->id),
    'Update News',
);



$this->menu = array(
    array('label' => 'Delete this news', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item news update?')),
    array('label' => 'Add another news item', 'url' => CHtml::normalizeUrl(array('itemNews/add', 'item_id' => $model->item->id))),
);
?>

<h1>Update an item news for: <?php echo $model->item->item; ?> </h1>

<?php


if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Item News Saved</div>';
}

echo $this->renderPartial('_form', array('model' => $model));
?>