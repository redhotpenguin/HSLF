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
$this->secondaryNav['name'] = 'Tags';
$this->secondaryNav['url'] = array('tag/index');

$this->widget('backend.extensions.ExtendedWidgets.GridView', array(
    'id' => 'tag-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'type' => 'striped',
    'columns' => array(
        array(
            'name' => 'display_name',
            'placeholder' => 'Search by Display Name',
        ),
        array(
            'name' => 'name',
            'placeholder' => 'Search by Name',
        ),
        array(
            'name' => 'type',
            'filter' => CHtml::dropDownList('Tag[type]', $model->type, array("" => 'Any') + Tag::model()->getTagTypes()),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
));
