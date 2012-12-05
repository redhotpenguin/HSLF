<?php
$this->breadcrumbs = array(
    'Districts' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Add a district', 'url' => array('create')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('district-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Districts</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$state_list = CHtml::listData(State::model()->findAll(), 'id', 'name');
$district_list = array('' => 'All') + District::model()->getTypeOptions();

$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'district-grid',
    'dataProvider' => $model->search(),
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
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'deleteConfirmation' => "js:'Deleting this District will also delete every ballot items associated to it, continue?'",
        ),
    ),
));
?>
