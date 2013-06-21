<?php
$tenant = Yii::app()->user->getLoggedInUserTenant();
$siteUrl = Yii::app()->params['site_url'] . '/client/' . $tenant->name;



$jsTagTypes = json_encode($tagTypes);

$ns = "var tagSelector_ns  = {site_url: '" . $siteUrl . "', modelName:'$modelName', tagTypes:$jsTagTypes};";


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
                <tr name="tagRow">
                    <td> 
                        <?php
                        echo $tag->display_name;
                        echo CHtml::hiddenField($modelName . '[tags][]', $tag->id);
                        ?>
                    </td>
                    <td>
                        <span name="deleteTagBtn" class="btn btn-warning" >remove</span>
                    </td>
                </tr>

                <?php
            endforeach;
        endif;
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">  
                <?php
                echo CHtml::textField('searchTag', null, array('placeholder' => 'Start entering a tag name', 'class' => 'span10 ui-autocomplete-input', 'autocomplete' => 'off'));
                ?>
            </td>    
        </tr>

    </tfoot>
</table>




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