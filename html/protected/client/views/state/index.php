<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'States';
$this->secondaryNav['url'] =array('state/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'state-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'abbr',
        'name',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'deleteConfirmation' => "js:'Deleting this State will also delete every districts and  items associated to it, continue?'",
            'template' => '{update}{delete}'
        ),
    ),
));
?>
