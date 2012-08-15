<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create a ballot item', 'url' => array('create')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
    array('label' => 'Export Scorecards', 'url' => array('exportScorecardCSV')),
);
?>

<h1>Manage Ballot Items</h1>

<?php
$state_list = array('' => 'All') + CHtml::listData(State::model()->findAll(), 'abbr', 'name');
$office_list = array('' => 'All') + CHtml::listData(Office::model()->findAll(), 'name', 'name');
$party_list = array('' => 'All') + CHtml::listData(Party::model()->findAll(), 'name', 'name');
$district_list = array('' => 'All') + District::model()->getTypeOptions();

$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'ballot-item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'template' => "{pager}{summary}\n{items}\n{pager}", // pagination on top and on bottom
    'columns' => array(
        array(
            'header' => 'Type',
            'name' => 'item_type',
            'value' => '$data->item_type',
            'filter' => CHtml::dropDownList('BallotItem[item_type]', $model->item_type, BallotItem::model()->getItemTypeOptions()),
            'htmlOptions' => array('width' => '85px'),
        ),
        array(
            'header' => 'Item',
            'name' => 'item',
        ),
        array(
            'name' => 'party',
            'value' => '$data->party->name',
            'filter' => CHtml::dropDownList('BallotItem[party]', $model->party, $party_list),
        ),
        array('name' => 'state_abbr',
            'header' => 'State',
            'value' => '$data->district->stateAbbr->name',
            'filter' => CHtml::dropDownList('BallotItem[state_abbr]', $model->state_abbr, $state_list),
        ),
        array(
            'header' => 'District Type',
            'name' => 'district_type',
            'value' => '$data->district->type',
            'filter' => CHtml::dropDownList('BallotItem[district_type]', $model->district_type, $district_list),
        ),
        array(
            'header' => 'District',
            'name' => 'district_number',
            'value' => '$data->district->number'
        ),
        array(
            'header' => 'Office',
            'name' => 'office_type',
            'value' => '$data->office->name',
            'filter' => CHtml::dropDownList('BallotItem[office_type]', $model->office_type, $office_list),
        ),
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
