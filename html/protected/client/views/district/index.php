<?php

$navBarItems = array(
    '',
    array('label' => 'Create', 'url' => array('create')),
    '',
    array('label' => 'Export', 'url' => array('exportCSV')),
    '',
);

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Districts';
$this->secondaryNav['url'] = array('district/index');


?>


<?php

$state_list = CHtml::listData(State::model()->findAll(), 'id', 'name');
$district_list = array('' => 'All') + District::model()->getTypeOptions();

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'district-grid',
    'dataProvider' => $model->with('state')->search(),
    'filter' => $model,
    'template' => "{pager}{summary}\n{items}\n{pager}", // pagination on top and on bottom
    'columns' => array(
        'id',
        array('name' => 'state_id',
            'header' => 'State',
            'value' => '$data->state->name',
            'filter' => CHtml::dropDownList('District[state_id]', $model->state, $state_list),
        ),
        array(
            'header' => 'District Type',
            'name' => 'type',
            'value' => '$data->type',
            'filter' => CHtml::dropDownList('District[type]', $model->type, $district_list),
        ),
        'number',
        'display_name',
        'locality',
        array(
            'template' => '{update}{delete}',
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'deleteConfirmation' => "js:'Deleting this District will also delete every items associated to it, continue?'",
        ),
    ),
));
?>
