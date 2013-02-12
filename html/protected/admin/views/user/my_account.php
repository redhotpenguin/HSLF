<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Account settings successfully updated</div>';
}
?>
<div class="form">

    <?php
    $model->password = '';

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('disabled' => 'true', 'class' => 'span6', 'maxlength' => 128, 'autocomplete' => 'off')); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('class' => 'span6', 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('class' => 'span6', 'maxlength' => 40)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'repeat_password'); ?>
        <?php echo $form->passwordField($model, 'repeat_password', array('class' => 'span6', 'maxlength' => 40)); ?>
        <?php echo $form->error($model, 'repeat_password'); ?>
    </div>





    <div class="buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->