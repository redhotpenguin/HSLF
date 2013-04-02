<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Next', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));
?>


<fieldset id="action">
    <h1>Action</h1>
</fieldset>

<div class="form">

    <?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/static/payload/payload.js');

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'push_composer',
            ));
    ?>

    <?php echo $form->errorSummary($payloadModel); ?>

    <div class="row-fluid">
        <?php echo $form->labelEx($payloadModel, 'title'); ?>
        <?php echo $form->textField($payloadModel, 'title', array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="For Share payload, appears as preview text on share screens, Facebook share title, email subject line. For push to post, not displayed on the app."></a>
        <?php echo $form->error($payloadModel, 'title'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($payloadModel, 'type');
        echo $form->dropDownList($payloadModel, 'type', $payloadModel->getTypeOptions());
        echo $form->error($payloadModel, 'type');
        ?>
    </div>
    <hr/>
    <div id="post_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($payloadModel, 'post_number'); ?>
            <?php echo $form->textField($payloadModel, 'post_number', array('placeholder' => '', 'size' => 60, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Ex: wordpress post id"></a>
            <?php echo $form->error($payloadModel, 'post_number'); ?>
        </div>
    </div>

    <div id="share_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($payloadModel, 'url'); ?>
            <?php echo $form->textField($payloadModel, 'url', array('placeholder' => '', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Target URL (for Facebook and Email shares)"></a>
            <?php echo $form->error($payloadModel, 'url'); ?>
        </div>



        <div class="row-fluid">
            <?php echo $form->labelEx($payloadModel, 'description'); ?>
            <?php echo $form->textArea($payloadModel, 'description', array('placeholder' => '', 'rows' => 6, 'cols' => 50, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Link description for Facebook excerpts"></a>
            <?php echo $form->error($payloadModel, 'description'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($payloadModel, 'tweet'); ?>
            <?php echo $form->textArea($payloadModel, 'tweet', array('placeholder' => '', 'rows' => 2, 'cols' => 50, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Exact content of the Tweet"></a>
            <?php echo $form->error($payloadModel, 'tweet'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($payloadModel, 'email'); ?>
            <?php echo $form->textField($payloadModel, 'email', array('placeholder' => '', 'size' => 60, 'maxlength' => 320, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Recipient email address (optional)"></a>

            <?php echo $form->error($payloadModel, 'email'); ?>
        </div>
    </div>


    <?php
    $this->endWidget();
    ?>

</div><!-- form -->