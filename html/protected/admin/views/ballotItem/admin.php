<?php
$this->breadcrumbs = array(
    'Ballot Items' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create a ballot item', 'url' => array('create')),
);
?>

<h1>Manage Ballot Items</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>



<?php
$state_list = CHtml::listData(State::model()->findAll(), 'abbr', 'name');

$state_list = array('' => 'All') + $state_list;



$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'ballot-item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'item_type',
            'value' => '$data->item_type',
            'filter' => CHtml::dropDownList('BallotItem[item_type]', $model->item_type, BallotItem::model()->getItemTypeOptions()),
            'htmlOptions'=>array('width'=>'85px'),
        ),
        //  'recommendation_id',
        // 'next_election_date',
        //'priority',

        'item',
        array(
            'name' => 'date_published',
            'value' => 'substr($data->date_published, 0, 10)', // get rid off the time stamp
            'htmlOptions'=>array('width'=>'70px'),
        ),
        array(
            'name' => 'party',
            'value' => '$data->party == "D" ? "Democrat" : "Republican"',
            'filter' => CHtml::dropDownList('BallotItem[party]', $model->party, array(
                '' => 'All',
                'D' => 'Democrat',
                'R' => 'Republican',
            )),
        ),
        array('name' => 'state_abbr',
            'header' => 'State',
            'value' => '$data->district->stateAbbr->name',
            'filter' => CHtml::dropDownList('BallotItem[state_abbr]', $model->state_abbr, $state_list),
        ),
        array(
            'name' => 'district_type',
            'value' => '$data->district->type',
            'filter' => CHtml::dropDownList('BallotItem[district_type]', $model->district_type, array(
                '' => 'All',
                'statewide' => 'Statewide',
                'congressional' => 'Congressional',
                'upper_house' => 'Upper House',
                'lower_house' => 'Lower House',
                'county' => 'County',
                'city' => 'City',
            )),
        ),
        /*
          array('name' => 'district_type',
          'header' => 'District type',
          'value' => '$data->district->type'
          ), */
        array('name' => 'district_number',
            'header' => 'District number',
            'value' => '$data->district->number'
        ),
        // 'election_result_id',

        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
