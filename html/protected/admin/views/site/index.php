<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php
    if (Yii::app()->user->id):
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the administration dashboard.</p>
            <p> <b><?php echo $total_ballot_page; ?></b> <a href="/admin/ballotItem/admin/">Ballot pages</a></p>
        </div>

        <div class="row">
            <div class="span3">
                <h2>Ballot Items</h2>
                <p>Add, edit, delete, search ballot items. </p>
                <p><a class="btn" href="/admin/ballotItem/admin/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Image Uploader</h2>
                <p>Upload images. </p>
                <p><a class="btn" href="/admin/upload/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Push Notifications</h2>
                <p>Send Rich Push Notifications to your users.</p>
                <p><a class="btn" href="https://go.urbanairship.com/apps/ouRCLPaBRRasv4K1AIw-xA/composer/rich-push/">More »</a></p>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="span3">
                <h2>Scorecard</h2>
                <p>Manage scorecard items.</p>
                <p><a class="btn" href="/admin/scorecardItem/admin/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Options</h2>
                <p>Remotely update your mobile application.</p>
                <p><a class="btn" href="/admin/option/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Dashboard users</h2>
                <p>Manage who can access this dashboard.</p>
                <p><a class="btn" href="/admin/user/">More »</a></p>
            </div>
        </div>

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

</div>