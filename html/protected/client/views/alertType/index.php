<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

if ($isAdmin) {
    array_push($navBarItems, array('label' => 'Export', 'url' => array('exportCSV')), ''
    );
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Alert Types';
$this->secondaryNav['url'] = array('alertType/index');
$this->introText = 'Alert Types are displayed as ON/OFF switches on the Personalize screen of your app. Users toggle these to opt in or out of receiving personalized alerts based on their interests. For a user to be tagged, Alert Types must be associated with an existing alert tag. Contact Winning Mark if you do not see the tag you want in the drop down when creating an Alert Type.</p><p class="helpText">To filter the list by Display Name, Tag or Category, type in the search bar and press enter. You can also sort the Alert Types by clicking the words “Display Name” or “Category.” Click the “Create” button above to add a new Alert Type. To export a full list of Alert Types and their details, click the “Export” button above.';


$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'alert-type-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
         array(
            'name' => 'display_name',
            'placeholder' => 'Search by Display Name',
        ),
        array(
            'header' => 'Tag',
            'name' => 'tag',
            'value' => '$data->tag->name',
            'placeholder' => 'Search by Tag',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
