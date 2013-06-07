<?php
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('MobileUser-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="search-form">
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
    'pager' => array(
        'cssFile' => false,
        'header' => false,
        'firstPageLabel' => 'First',
        'prevPageLabel' => 'Previous',
        'nextPageLabel' => 'Next',
        'lastPageLabel' => 'Last',
    ),
    'columns' => array(
        array(
            'name' => '_id',
            'header' => 'ID'
        ),
        array(
            'name' => 'device_type',
            'header' => 'Device Type',
        ),
        array(
            'name' => 'registration_date',
            'header' => 'Registration Date',
            'value' => 'isset($data["registration_date"]) ? date("l M j Y  - G:i:s (T)", $data["registration_date"]->sec) : "N/A"'
       ),
        array(
            'name' => 'last_connection_date',
            'header' => 'Last Connection Date',
            'value' => 'isset($data["last_connection_date"]) ? date("l M j Y  - G:i:s (T)", $data["last_connection_date"]->sec) : "N/A"'
       ),
       array(
            'name' => 'app_version',
            'header' => 'Version',
        ),
        array
            (
            'class' => 'bootstrap.widgets.TbButtonColumn',
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