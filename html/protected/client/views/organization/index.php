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
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";


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