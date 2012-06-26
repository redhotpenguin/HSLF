<?php
$this->breadcrumbs = array(
    'Application Users' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List application users', 'url' => array('index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('application-user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Application Users</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>


<?php
if (Yii::app()->user->getState('role') == 'admin') {
    $button_template = '{view} {update} {delete} ';
} else {
    $button_template = '{view}';
}

$state_list = CHtml::listData(State::model()->findAll(), 'abbr', 'name');

$state_list = array('' => 'All') + $state_list;

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'application-users-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        //'device_token',
        'latitude',
        'longitude',
  
        array('name' => 'state_abbr',
            'header' => 'State',
            'value' => '$data->district->stateAbbr->name',
            'filter' => CHtml::dropDownList('Application_user[state_abbr]', $model->state_abbr, $state_list),
        ),
        
             array(
            'header' => 'Level',
            'name' => 'district_type',
            'value' => '$data->district->type',
            'filter' => CHtml::dropDownList('Application_user[district_type]', $model->district_type, array(
                '' => 'All',
                'statewide' => 'Statewide',
                'congressional' => 'Congressional',
                'upper_house' => 'Upper House',
                'lower_house' => 'Lower House',
                'county' => 'County',
                'city' => 'City',
            )),
        ),
   
        array('name' => 'district_number',
            'header' => 'District number',
            'value' => '$data->district->number'
        ),
        
        
        array(
            'class' => 'CButtonColumn',
            'template' => $button_template,
        ),
    ),
));
?>
