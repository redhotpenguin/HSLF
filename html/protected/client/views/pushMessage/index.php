<?php

$navBarItems = array(
    '',
    array('label' => 'New Message', 'url' => array('pushMessage/composer')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    ''
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Push Notifications';
$this->secondaryNav['url'] = array('pushMessage/index');

$this->header = 'History';
$this->introText = 'Below is the history of all push notifications you have sent. To filter the history by message title, type in the search bar and press enter. You can also filter using the calendar and drop down menus or order the notifications by clicking the words “Message,” “Creation Date” or “Recipient.” To export a full history of notifications and their details, click the “Export” button above.</p><p class="helpText">Click the "Stats" icon to see more details about a notification. Click the "Update" or “Delete” icon to edit the text of a message in the Alert Inbox or remove a notification from the Alert Inbox. To send a new push notification, click the “New Message” button above.</p>';

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
            'buttons' => array
                (
                'view' => array(
                    'label' => 'Stats',
                    'icon' => 'icon-signal'
                )
            )
        ),
    ),
    'afterAjaxUpdate' => 'function reinstallDatePicker(id, data) { $("#datepicker_for_creation_date").datepicker();}',
));

