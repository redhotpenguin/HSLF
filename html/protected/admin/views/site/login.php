<?php
$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
        'id' => 'login-form',
        // 'id'=>'verticalForm',
        'htmlOptions' => array('class' => 'well'),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>


    <?php echo $form->labelEx($model, 'username'); ?>
    <?php echo $form->textField($model, 'username'); ?>
    <?php echo $form->error($model, 'username'); ?>



    <?php echo $form->labelEx($model, 'password'); ?>
    <?php echo $form->passwordField($model, 'password'); ?>
    <?php echo $form->error($model, 'password'); ?>



    <div class="checkbox">
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
        <span class="help-block">Remember me next time</div>
        <?php echo $form->error($model, 'rememberMe'); ?>
</div>


<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType' => 'submit', 'label' => 'Connect')); ?>


<?php $this->endWidget(); ?>
</div><!-- form -->
