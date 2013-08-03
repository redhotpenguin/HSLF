<?php
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');

$this->introText = 'Below is a list of all users registered with your app. To search by ID, Name or Email, type in a search bar and press enter. To filter by Device Type, select from the drop down menu.</p><p class="helpText">Click the "View" icon to see details about the user or the "Delete" icon to remove the user from the dashboard; this will not remove the app from the user’s phone but will delete the user record from your database. If the user opens the app again, a new record will be created.';



Yii::app()->clientScript->registerScript('search', "
    
function submitSearchForm(){
	$.fn.yiiGridView.update('MobileUser-grid', {
		data: $(this).serialize()
	});
	return false;
}
    $('#MobileUser_device_type').change(submitSearchForm);
    $('.search-form form').submit(submitSearchForm);
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
                    'label' => 'View',
                    'url' => 'Yii::app()->createUrl("mobileUser/view",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
                'delete' => array
                    (
                    'label' => 'Delete',
                    'url' => 'Yii::app()->createUrl("mobileUser/delete",array("id"=>$data["_id"]->{\'$id\'}))',
                ),
            ),
        ),
    ),
));