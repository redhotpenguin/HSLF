<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'push_composer',
        ));
?>
<fieldset id="recipients">
    <h1>Audience</h1>
</fieldset>
<div class="form">


    <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>
    <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


    <div class="row-fluid">
        <div class="span15">
            <div id="tag_list">
                <h4>Tags: </h4>
            </div>

            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Add a tag',
                'type' => 'info',
                'htmlOptions' => array(
                    'id' => 'addTagBtn'
                ),
            ));
            ?>
        </div>

    </div>
</div>

<div class="clearfix"></div>
<hr/>
<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'size' => 'large', 'label' => 'Next', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));
$this->endWidget();
