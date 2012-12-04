<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'well form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128, 'autocomplete' => 'off')); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 40)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'repeat_password'); ?>
        <?php echo $form->passwordField($model, 'repeat_password', array('size' => 60, 'maxlength' => 40)); ?>
        <?php echo $form->error($model, 'repeat_password'); ?>
    </div>


    <div class="">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'role');
        echo $form->dropDownList($model, 'role', $model->getRoleOptions());
        echo $form->error($model, 'role');
        ?>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->