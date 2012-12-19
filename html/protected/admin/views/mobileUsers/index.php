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
        array(
            'name' => '_id',
            'header' => 'ID'
        ),
        array(
            'name' => 'device_identifier',
            'header' => 'Device Identifier'
        ),
        array(
            'name' => 'device_type',
            'header' => 'Device Type'
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}{delete}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("mobileUsers/view",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
                'delete' => array
                    (
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("mobileUsers/delete",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
            ),
        ),
    ),
));
?>
