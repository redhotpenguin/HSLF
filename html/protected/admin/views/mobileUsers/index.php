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
        "email",
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => '',
                    //   'url' => '"http://www.google.fr"' // works
                    'url' => 'Yii::app()->createUrl("mobileUsers/view",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
            ),
        ),
    ),
));
?>
