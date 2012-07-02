<?php

$this->breadcrumbs = array(
    'Ballot Items' => array('ballotItem/index'),
    $model->ballotItem->item => array('ballotItem/update', 'id' => $model->ballotItem->id),
    'Add news update',
);

?>

<h1>Add a scorecard item for: <?php echo $model->ballotItem->item; ?> </h1>

<?php

echo $this->renderPartial('_form', array('model' => $model  ));
?>