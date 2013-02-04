<?php
$this->breadcrumbs = array(
    'Push Messages' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'Create a push message', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('push-message-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Push Messages</h1>


<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'push-message-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'name' => 'alert',
            'value' => 'substr($data->alert, 0, 30)."...";'
        ),
        'creation_date',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));