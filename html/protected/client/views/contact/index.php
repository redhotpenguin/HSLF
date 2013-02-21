<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Contacts';
$this->secondaryNav['url'] = array('contact/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'contact-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'title',
        /*
          'phone_number',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
?> 