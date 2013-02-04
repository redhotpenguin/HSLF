<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'option-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span7',)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'value'); ?>
        <?php echo $form->textArea($model, 'value', array('rows' => 6, 'class' => 'span7', 'cols' => 10)); ?>
        <?php echo $form->error($model, 'value'); ?>
    </div>

    <div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type'=>'primary' ,'label' => 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->