<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'option-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'well form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'name'); ?>
<?php echo $form->textField($model, 'name'); ?>
<?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'value'); ?>
<?php echo $form->textArea($model, 'value', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'value'); ?>
    </div>

    <div class="buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->