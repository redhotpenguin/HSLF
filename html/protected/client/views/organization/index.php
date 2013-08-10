<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
    
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Organizations';
$this->secondaryNav['url'] = array('organization/index');

$this->header = 'Organization';
$this->introText = 'Below is a list of all the Organizations in your app. To filter the list by Name, Friendly Name or Website, type in the search bar and press enter. You can also sort the organizations by clicking the words “Organization Name,” “Friendly Name” or “Website.” Click the "Update" icon to edit an existing Organization or click the "Delete" icon to remove the Organization from your app. To add a new Organization, click the “Create” button above. To export a full list of Organizations and their details, click the “Export” button above.';

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'organization-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped',
    'filter' => $model,
    'columns' => array(
        'name',
        'display_name',
        array(
            'name' => 'website',
            'type' => 'raw',
            'value' => ' Chtml::link( $data->website, $data->website, array("target"=>"_blank")) '
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));