<?php
$this->breadcrumbs = array(
    'Parties' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Add a party', 'url' => array('create')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('party-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Parties</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'party-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        'abbr',
        'initial',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
        ),
    ),
));
?>

