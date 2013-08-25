<?php
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');

$this->introText = 'Below is a list of all users registered with your app. To search by ID, Name or Email, type in a search bar and press enter. To filter by Device Type, select from the drop down menu.</p><p class="helpText">Click the "View" icon to see details about the user or the "Delete" icon to remove the user from the dashboard; this will not remove the app from the user’s phone but will delete the user record from your database. If the user opens the app again, a new record will be created.';

$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'MobileUser-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'pager' => array(
        'cssFile' => false,
        'header' => false,
        'firstPageLabel' => 'First',
        'prevPageLabel' => 'Previous',
        'nextPageLabel' => 'Next',
        'lastPageLabel' => 'Last',
    ),
    'columns' => array(
        array(
            'name' => '_id',
            'header' => 'App ID',
            'placeholder' => 'Search by App ID',
        ),
        array(
            'name' => 'device_type',
            'header' => 'Device Type',
            'filter' => CHtml::dropDownList('MobileUser[device_type]', $model->device_type, array("" => "All") + $model->getDeviceTypes()),
        ),
        array(
            'name' => 'name',
            'header' => 'Name',
            'placeholder' => 'Search by Name',
        ),
        array(
            'name' => 'email',
            'header' => 'Email',
            'placeholder' => 'Search by Email',
        ),
        array(
            'name' => 'app_version',
            'header' => 'Version',
            'placeholder' => 'Search by Version',
        ),
        array
            (
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}{delete}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => 'View',
                    'url' => 'Yii::app()->createUrl("mobileUser/view",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
                'delete' => array
                    (
                    'label' => 'Delete',
                    'url' => 'Yii::app()->createUrl("mobileUser/delete",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
            ),
        ),
    ),
));