<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));


$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Next', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'push_composer',
        ));
?>

<div class="clearfix"></div>
<fieldset id="recipients">
    <h1>Recipients</h1>
</fieldset>
<div class="form">


    <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>
    <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


    <div id="tag_list">
        <h4>Tags: </h4>
        <em>Users who have at least one of the following tags</em>

        <div class="row tagBox" id="original_tag">
            <?php
            echo CHtml::textField("Tags[]", "", array('class' => 'tagInput'));
            ?>
        </div>
    </div>

    <div class="row">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Add a tag',
            'htmlOptions' => array('id' => 'add_tag_btn')
        ));
        ?>
    </div>

</div>

<?php
$this->endWidget();
?>
