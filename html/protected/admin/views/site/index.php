<?php $this->pageTitle = Yii::app()->name; ?>

<h1>WM Mobile: HSLF </h1>

<div>




    <?php
    if (Yii::app()->user->id):
        ?>

        <p> <b><?php echo $total_app_users; ?></b> <a href="/admin/application_users">Application users</a></p>
        <p> <b><?php echo $total_ballot_page; ?></b> <a href="/admin/ballotItem">Ballot pages</a></p>
        
        <?php
    else: // display login form if user not logged in
        ?>

    </div>


    <p>Please fill out the following form with your login credentials:</p>

    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="row">
            <?php echo $form->labelEx($model, 'username'); ?>
    <?php echo $form->textField($model, 'username'); ?>
    <?php echo $form->error($model, 'username'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'password'); ?>
    <?php echo $form->passwordField($model, 'password'); ?>
    <?php echo $form->error($model, 'password'); ?>

        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
    <?php echo $form->label($model, 'rememberMe'); ?>
    <?php echo $form->error($model, 'rememberMe'); ?>
        </div>

        <div class="row buttons">
        <?php echo CHtml::submitButton('Login'); ?>
        </div>

        <?php $this->endWidget(); ?>

    <?php
    endif; //end test is user logged in
    ?>