<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Options';
$this->secondaryNav['url'] =array('option/index');



$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'option-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        array(
            'name' => 'value',
            'value' => 'substr($data->value,0,100);'
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
