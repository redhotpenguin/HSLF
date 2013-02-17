<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Scorecard Items';
$this->secondaryNav['url'] =array('scorecardItem/index');

$office_list = array('' => 'All') + CHtml::listData(Office::model()->findAll(), 'name', 'name');


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'scorecard-item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'description',
        array(
            'header' => 'Office',
            'name' => 'office_type',
            'value' => '$data->office->name',
            'filter' => CHtml::dropDownList('ScorecardItem[office_type]', $model->office_type, $office_list),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
?>
