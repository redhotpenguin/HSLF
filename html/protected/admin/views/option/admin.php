<?php
$this->breadcrumbs = array(
    'Options' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Add an option', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('option-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Options</h1>

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
if (isAdmin())
    $template = '{view}{update}{delete}';
else
    $template = '{view}{update}';

$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'option-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        'value',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'template'=>$template,
        ),
    ),
));
?>
