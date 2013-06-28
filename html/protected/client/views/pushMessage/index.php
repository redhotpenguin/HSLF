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

$this->header = 'History';
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";
?>


<?php

$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'push-message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'type' => 'striped',
    'htmlOptions' => array(
        'class' => 'grid-view',
    ),
    'columns' => array(
        array(
            'name' => 'alert',
            'placeholder' => 'Search by message',
            'htmlOptions' => array('width' => '50%'),
        ),
        array(
            'name' => 'creation_date',
            'value' => 'date("F d, Y - h:i A",  strtotime( $data->creation_date ) )',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'creation_date',
                'language' => 'en',
                'i18nScriptFile' => 'jquery.ui.datepicker-ja.js',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_creation_date',
                    'size' => '10',
                    'placeholder' => 'Search by date',
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => true,
                )
                    ), true),
            'htmlOptions' => array('width' => '20%'),
        ),
        array(
            'placeholder' => 'Recipient',
            'header' => 'Recipient',
            'name' => 'recipient_type',
            'filter' => CHtml::dropDownList('PushMessage[recipient_type]', $model->recipient_type, array("" => "Any") + $model->getRecipientTypes()),
            'htmlOptions' => array('width' => '15%'),
        ),
        array(
            'name' => 'delivered',
            'header' => 'Status',
            'value' => '$data->isDelivered() ? "Delivered" : "Not delivered";',
            'filter' => CHtml::dropDownList('PushMessage[delivered]', $model->delivered, array('any' => 'Any', 'true' => 'Delivered', 'false' => 'Not Delivered')),
            'htmlOptions' => array('width' => '10%'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}{update}{delete}',
        //   'htmlOptions' => array('style' => 'vertical-align: middle;'),
        ),
    ),
    'afterAjaxUpdate' => 'function reinstallDatePicker(id, data) { $("#datepicker_for_creation_date").datepicker();}',
));

