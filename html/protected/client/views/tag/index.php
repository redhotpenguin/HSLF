<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Tags';
$this->secondaryNav['url'] =array('tag/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'tag-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'display_name',
        'type',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}'
        ),
    ),
));
