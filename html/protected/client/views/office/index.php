<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Offices';
$this->secondaryNav['url'] =array('office/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'office-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        array(// display a column with "view", "update" and "delete" buttons
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));

