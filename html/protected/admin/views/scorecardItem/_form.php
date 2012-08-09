<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'scorecard-item-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
<?php echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span7' , 'maxlength' => 4096)); ?>
<?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
<?php echo $form->textArea($model, 'description', array('rows' => 6, 'class' => 'span7' , 'cols' => 10)); ?>
<?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php
        $office_list = CHtml::listData(Office::model()->findAll(), 'id', 'name');

        echo $form->labelEx($model, 'office_id');
        echo $form->dropDownList($model, 'office_id', $office_list);
        echo $form->error($model, 'office_id');
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->