<?php
$this->breadcrumbs = array(
    'States' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Manage states', 'url' => array('admin')),
    array('label' => 'Add another state', 'url' => array('create')),
    array('label' => 'Export to CSV', 'url' => array('exportCSV')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('state-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage States</h1>

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
$this->widget('bootstrap.widgets.BootGridView', array(
    'id' => 'state-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'abbr',
        'name',
        array(
            'class' => 'bootstrap.widgets.BootButtonColumn',
            'deleteConfirmation' => "js:'Deleting this State will also delete every districts and ballot items associated to it, continue?'",
        ),
    ),
));
?>
