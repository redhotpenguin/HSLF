<?php
$this->breadcrumbs = array(
    'Mobile Users' => array('index'),
    'Browse',
);
?>

<h1>Mobile Users</h1>

<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('MobileUser-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

echo '<b>' . CHtml::link('Search', '#', array('class' => 'search-button')) . '</b>';
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'MobileUser-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array(
            'name' => '_id',
            'header' => 'ID'
        ),
        array(
            'name' => 'device_type',
            'header' => 'Device Type'
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}{delete}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("mobileUser/view",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
                'delete' => array
                    (
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("mobileUser/delete",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
            ),
        ),
    ),
));
?>
