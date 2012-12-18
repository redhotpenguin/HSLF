<?php
$this->breadcrumbs = array(
    'Mobile users',
);

?>

<h1>Mobile Users</h1>

<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'vote-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        '_id',
        'device_identifier',
        "email"
    ),
));
?>
