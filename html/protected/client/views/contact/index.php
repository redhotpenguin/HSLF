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
$this->secondaryNav['name'] = 'Contacts';
$this->secondaryNav['url'] = array('contact/index');

$this->header = 'Contacts';
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'contact-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'type' => 'striped',
    'columns' => array(
        'first_name',
        'last_name',
        'email',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
?> 