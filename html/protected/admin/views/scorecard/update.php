<?php

$this->breadcrumbs = array(
    'Ballot Items' => array('ballotItem/index'),
    $model->ballotItem->item => array('ballotItem/update', 'id' => $model->ballotItem->id),
    'Update News',
);

$this->menu = array(
    array('label' => 'Delete this scorecard item', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this scorecard item?')),
);
?>

<h1>Update a scorecard item for: <?php echo $model->ballotItem->item; ?> </h1>

<?php

echo $this->renderPartial('_form', array('model' => $model  ));
?>