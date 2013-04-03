<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>


<div class="row-fluid">
    <div class="login-box">
        <h2>Login to your account</h2>

        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'login-form',
            // 'id'=>'verticalForm',
            'htmlOptions' => array('class' => 'form-horizontal'),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>
        <fieldset>

            <div class="input-prepend" title="Username">
                <span class="add-on"><i class="halflings-icon user"></i></span>

                <?php
                echo $form->textField($model, 'username', array('class' => 'input-large span10', 'placeholder' => 'username'));
                ?>


            </div>


            <div class="clearfix"></div>

            <div class="input-prepend" title="Password">
                <span class="add-on"><i class="halflings-icon lock"></i></span>
                <?php
                echo $form->passwordField($model, 'password', array('class' => 'input-large span10', 'placeholder' => 'password'));
                ?>
            </div>

            <div class="clearfix"></div>
            
            <div class="prepend">
                <?php
                echo $form->error($model, 'username');

                echo $form->error($model, 'password');
                ?>
            </div>

            <div class="button-login">	
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Login'));
                ?>
            </div>

        </fieldset>
        <?php $this->endWidget(); ?>
    </div>
</div>
