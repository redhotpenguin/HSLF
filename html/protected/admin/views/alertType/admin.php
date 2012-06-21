<?php
$this->breadcrumbs = array(
    'Alert Types' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Add an alert type', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('alert-type-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Alert Types</h1>

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
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'alert-type-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'display_name',
        array(
            'header' => 'Tag',
            'name' => 'tag',
            'value' => '$data->tag->name',
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>