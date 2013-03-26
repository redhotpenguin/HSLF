<?php
$tenant = Yii::app()->user->getLoggedInUserTenant();
$siteUrl = $siteUrl = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$ns = "var tagSelector_ns  = {site_url: '" . $siteUrl ."'};";


Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);

if (!empty($checkBoxList)):
    ?>
    <table id="table_tag" class="table table-bordered table-striped">

        <?php
        foreach ($checkBoxList as $tagId => $item):
            ?>
            <tr>
                <td> <?php echo $item['name']; ?> </td>
                <td> <?php echo CHtml::checkBox($modelName . '[tags][]', $item['checked'], array('value' => $tagId)); ?> </td>
            </tr>

            <?php
        endforeach;
        ?>
    </table><?php
else:
    echo 'No tags avalaible';
endif;

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