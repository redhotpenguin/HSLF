<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
);


if ($isAdmin) {
    array_push($navBarItems, array(
        'label' => 'Export',
        'class' => 'bootstrap.widgets.TbMenu',
        'htmlOptions' => array('class' => 'pull-right'),
        'items' => array(
            array('label' => 'Export ballot items', 'url' => array('exportCSV')),
            '',
            array('label' => 'Export news', 'url' => array('exportNewsCSV')),
            '',
            array('label' => 'Export organizations ', 'url' => array('exportOrganizationCSV')),
        ),
        '',
    ));
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Ballot Items';
$this->secondaryNav['url'] = array('item/index');


$state_list = array('' => 'All') + CHtml::listData(State::model()->findAll(), 'id', 'name');
$district_list = array('' => 'All') + District::model()->getTypeOptions();
$item_type_list = array('' => 'All') + BallotItem::model()->getItemTypeOptions();

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
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
            'template' => '{update}{delete}'
        ),
    ),
));
