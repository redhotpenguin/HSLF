<?php
if (getParam('updated') == '1' || getParam('created') == '1') {
    echo '<div class="update_box btn-success">Account settings successfully updated</div>';
}
?>

<div class="row-fluid">
    <div class="login-box">
        <h2>Account settings</h2>



        <?php
        $model->password = '';

        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'class' => 'form-horizontal'),
                ));
        ?>

        <fieldset>
            <?php echo $form->errorSummary($model); ?>

            <div class="input-prepend" title="Username">
                <span class="add-on"><i class="halflings-icon user"></i></span>

                <?php
                echo $form->textField($model, 'username', array('autocomplete' => 'off', 'disabled' => 'true', 'class' => 'input-large span10', 'placeholder' => 'username'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="input-prepend" title="Password">
                <span class="add-on"><i class="halflings-icon lock"></i></span>
                <?php
                echo $form->passwordField($model, 'password', array('class' => 'input-large span10', 'placeholder' => 'password'));
                ?>
            </div>

            <div class="input-prepend" title="Repeat Password">
                <span class="add-on"><i class="halflings-icon lock"></i></span>
                <?php
                echo $form->passwordField($model, 'repeat_password', array('class' => 'input-large span10', 'placeholder' => 'password'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="input-prepend" title="Email">
                <span class="add-on"><i class="halflings-icon email"></i></span>

                <?php
                echo $form->textField($model, 'email', array('class' => 'input-large span10', 'placeholder' => 'email'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="button-login">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
            </div>
        </fieldset>
        <?php $this->endWidget(); ?>


    </div>

</div>