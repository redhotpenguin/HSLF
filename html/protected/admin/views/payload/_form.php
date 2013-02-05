<?php
/* @var $this SharePayloadController */
/* @var $model Payload */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/static/payload/payload.js');


    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'payload-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('placeholder' => 'title', 'size' => 60, 'maxlength' => 512, 'class' => 'span11')); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions());
        echo $form->error($model, 'type');
        ?>
    </div>
    <hr/>
    <div id="post_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'post_number'); ?>
            <?php echo $form->textField($model, 'post_number', array('placeholder' => 'Ex: wordpress post id', 'size' => 60, 'class' => 'span11')); ?>
            <?php echo $form->error($model, 'post_number'); ?>
        </div>
    </div>

    <div id="share_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'url'); ?>
            <?php echo $form->textField($model, 'url', array('placeholder' => 'Target URL', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11')); ?>
            <?php echo $form->error($model, 'url'); ?>
        </div>



        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description', array('placeholder' => 'Link description for Facebook excerpt and email body', 'rows' => 6, 'cols' => 50, 'class' => 'span11')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'tweet'); ?>
            <?php echo $form->textArea($model, 'tweet', array('placeholder' => 'Content of the Tweet', 'rows' => 2, 'cols' => 50, 'class' => 'span11')); ?>
            <?php echo $form->error($model, 'tweet'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('placeholder' => 'Recipient email address', 'size' => 60, 'maxlength' => 320, 'class' => 'span11')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <br/>

    <div class="row-fluid buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->