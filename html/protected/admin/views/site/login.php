<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>


    <div class="form">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'login-form',
            // 'id'=>'verticalForm',
            'htmlOptions' => array('class' => 'well'),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>

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



        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Login')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
