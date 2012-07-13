<?php

$this->breadcrumbs = array(
    'Ballot Items' => array('ballotItem/index'),
    $model->ballotItem->item => array('ballotItem/update', 'id' => $model->ballotItem->id),
    'Add news update',
);

?>

<h1>Add a scorecard item for: <?php echo $model->ballotItem->item; ?> </h1>

<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Scorecard item saved</div>';
}
echo $this->renderPartial('_form', array('model' => $model  ));
?>