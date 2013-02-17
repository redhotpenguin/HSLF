<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Push Messages';
$this->secondaryNav['url'] =array('pushMessage/index');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'push-message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'alert',
            'value' => 'substr($data->alert, 0, 30)."...";'
        ),
        'creation_date',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));