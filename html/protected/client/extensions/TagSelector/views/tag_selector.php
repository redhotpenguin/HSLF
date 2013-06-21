<?php
$tenant = Yii::app()->user->getLoggedInUserTenant();
$siteUrl = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$ns = "var tagSelector_ns  = {site_url: '" . $siteUrl . "', modelName:'$modelName'};";

$findTagUrl = $siteUrl . "/tag/findTag";
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
Yii::app()->clientScript->registerScript('tag-selector-script', $ns, CClientScript::POS_HEAD);
?>
<table id="modelTagTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th colspan="2">Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($modelTags)):
            foreach ($modelTags as $tag):
                ?>
                <tr>
                    <td> <?php echo $tag->display_name; ?> </td>
                    <td> <?php echo CHtml::textField($modelName . '[tags][]', $tag->id); ?> </td>
                </tr>

                <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>


<div class="row-fluid">
    <?php
    echo CHtml::textField('searchTag', null, array('placeholder' => 'tag name', 'class' => 'ui-autocomplete-input', 'autocomplete' => 'off'));
    ?>
</div>




<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'createTagModal',
    'autoOpen' => false,
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Create a new tag</h4>
</div>

<div class="modal-body">
    <?php
    $this->render('tag_creator', array('tagTypes' => $tagTypes));
    ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'label' => 'Save',
        'url' => '#',
        'htmlOptions' => array(
            'id' => 'save_tag_btn',
            'data-dismiss' => 'modal'),
    ));
    ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?><?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Create a new tag',
    'type' => 'info',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#createTagModal',
    ),
));
?>