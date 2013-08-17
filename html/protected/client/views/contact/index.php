<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    ''
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Contacts';
$this->secondaryNav['url'] = array('contact/index');

$this->header = 'Contacts';
$this->introText = 'Below is a list of all the Contacts saved in your dashboard, which can be added to an Organization from the Organization’s page. To filter the list by First Name, Last Name or Email, type in the search bar and press enter. You can also sort the contacts by clicking the words “First Name,” “Last Name” or “Email.”</p><p class="helpText">Click the “Create” button above to add a new Contact. Click the "Update" icon to edit an existing Contact or the "Delete" icon to remove a Contact from your app. To export a full list of Contacts and their details, click the “Export” button above. </p>';

$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'contact-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'type' => 'striped',
    'columns' => array(
        array(
            'name' => 'first_name',
            'placeholder' => 'Search by First Name',
        ),
        array(
            'name' => 'last_name',
            'placeholder' => 'Search by Last Name',
        ),
        array(
            'name' => 'email',
            'placeholder' => 'Search by Email',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
?> 