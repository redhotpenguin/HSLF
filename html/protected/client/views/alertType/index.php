<?php
$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Alert Types';
$this->secondaryNav['url'] = array('alertType/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'alert-type-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'display_name',
        array(
            'header' => 'Tag',
            'name' => 'tag',
            'value' => '$data->tag->name',
        ),
        'category',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
