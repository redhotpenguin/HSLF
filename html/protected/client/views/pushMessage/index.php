<?php
$navBarItems = array(
    '',
    array('label' => 'New Message', 'url' => array('pushMessage/composer')),
    '',
);

if ($isAdmin) {
    array_push($navBarItems, array('label' => 'Export', 'url' => array('exportCSV')), ''
    );
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Push Notifications';
$this->secondaryNav['url'] = array('pushMessage/index');
?>

<h4>History</h4>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'push-message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'alert',
        ),
        array(
            'name' => 'creation_date',
            'value' => 'date("Y-m-d",  strtotime( $data->creation_date ) )',
        ),
        array(
            'header' => 'Recipient',
            'value' => '$data->recipient_type',
            'filter' => CHtml::dropDownList('PushMessage[recipient_type]', $model->recipient_type, array("" => "Any") + $model->getRecipientTypes()),
        ),
        array(
            'name' => 'delivered',
            'header' => 'Status',
            'value' => '$data->isDelivered() ? "Delivered" : "Not delivered";',
            'filter' => CHtml::dropDownList('PushMessage[delivered]', $model->delivered, array('any' => 'Any', 'true' => 'Delivered', 'false' => 'Not Delivered')),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}{update}{delete}'
        ),
    ),
));