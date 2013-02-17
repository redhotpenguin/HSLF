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
$this->secondaryNav['url'] =array('organization/index');


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'vote-grid',
    'dataProvider' => $model->search(),
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