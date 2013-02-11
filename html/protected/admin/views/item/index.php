<?php

$navBarItems = array(
    '',
    array('label' => 'Create a ballot item', 'url' => array('create')),
    '',
    array('label' => 'Export ballot items', 'url' => array('exportCSV')),
    '',
    array('label' => 'Export news', 'url' => array('exportNewsCSV')),
    '',
    array('label' => 'Export organizations ', 'url' => array('exportOrganizationCSV')),
    '',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => '',
    'brandUrl' => '#',
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));


$state_list = array('' => 'All') + CHtml::listData(State::model()->findAll(), 'id', 'name');
$district_list = array('' => 'All') + District::model()->getTypeOptions();
$item_type_list = array('' => 'All') + Item::model()->getItemTypeOptions();

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => "{pager}{summary}\n{items}\n{pager}", // pagination on top and on bottom
    'columns' => array(
        array(
            'header' => 'Type',
            'name' => 'item_type',
            'value' => '$data->item_type',
            'filter' => CHtml::dropDownList('Item[item_type]', $model->item_type, $item_type_list),
            'htmlOptions' => array('width' => '85px'),
        ),
        array(
            'header' => 'Item',
            'name' => 'item',
        ),
        array('name' => 'state_id',
            'header' => 'State',
            'value' => '$data->district->state->name',
            'filter' => CHtml::dropDownList('Item[state_id]', $model->state_id, $state_list),
        ),
        array(
            'header' => 'District Type',
            'name' => 'district_type',
            'value' => '$data->district->type',
            'filter' => CHtml::dropDownList('Item[district_type]', $model->district_type, $district_list),
        ),
        array(
            'header' => 'District',
            'name' => 'district_display_name',
            'value' => '$data->district->display_name'
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}'
        ),
    ),
));
