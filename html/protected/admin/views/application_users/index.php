<?php
$this->breadcrumbs = array(
    'Application Users',
);

$this->menu = array(
    array('label' => 'Manage application users', 'url' => array('admin')),
);
?>

<h1>Application Users</h1>

<?php
if (Yii::app()->user->getState('role') == 'admin') {
    $button_template = '{view} {update} {delete} ';
} else {
    $button_template = '{view}';
}



$dataProvider->pagination->pageSize = 50;
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'device_token',
        array(
            'header' => 'District',
            'value' => '$data->district->state_abbr." ".$data->district->type." ".$data->district->number'
        ),
        'type',
        'registration',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'CButtonColumn',
            'template' => $button_template,
        ),
    ),
));
?>