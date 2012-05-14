<?php
$this->breadcrumbs = array(
    'Candidates' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'Add a candidate', 'url' => array('create')),
    array('label' => 'Manage candidates', 'url' => array('admin')),
    array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this candidate?')),
);
?>

<h1>Update <?php echo $model->full_name; ?></h1>

<?php
echo $this->renderPartial('_form', array(
    'model' => $model,
    'candidate_issue' => $candidate_issue,
    'validate_candidate_issues' => $validate_candidate_issues
));
?>