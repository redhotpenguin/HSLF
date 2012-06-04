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
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'ballot-item-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'item_type',
        //  'recommendation_id',
        //  'next_election_date',

        'priority',
        //'date_published',

        'item',
        'party',
        array('name' => 'state_abbr',
            'header' => 'State',
            'value' => '$data->district->state_abbr'
        ),
        array('name' => 'district_type',
            'header' => 'District type',
            'value' => '$data->district->type'
        ),
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
