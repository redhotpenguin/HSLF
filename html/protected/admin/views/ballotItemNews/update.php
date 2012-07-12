<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('ballotItem/index'),
    $model->ballotItem->item => array('ballotItem/update', 'id' => $model->ballotItem->id),
    'Update News',
);



$this->menu = array(
    array('label' => 'Delete this news', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this ballot item news update?')),
    array('label' => 'Adde another news item', 'url' => CHtml::normalizeUrl(array('ballotItemNews/add', 'ballot_item_id' => $model->ballotItem->id))),
);
?>

<h1>Update a ballot item news for: <?php echo $model->ballotItem->item; ?> </h1>

<?php


if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Ballot Item News Saved</div>';
}

echo $this->renderPartial('_form', array('model' => $model));
?>