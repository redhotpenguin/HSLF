<?php $this->pageTitle = Yii::app()->name; ?>

<div>

    <?php
    if (Yii::app()->user->id):
        ?>
        <div class="hero-unit">
            <h1>Dashboard</h1>
            <p>Welcome to the HSLF app dashboard.</p>

            <p> <b><?php echo $total_app_users; ?></b> <a href="/admin/application_users">Application users</a></p>
            <p> <b><?php echo $total_ballot_page; ?></b> <a href="/admin/ballotItem">Ballot pages</a></p>
        </div>

        <div class="row">
            <div class="span3">
                <h2>Ballot Items</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="/admin/ballotItem/admin/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Media Manager</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="/admin/upload/">More »</a></p>
            </div>
            <div class="span3">
                <h2>Push Notifications</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn" href="https://go.urbanairship.com/apps/ouRCLPaBRRasv4K1AIw-xA/composer/rich-push/">More »</a></p>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="span3">
                <h2>Application Users</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="/admin/application_users/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Options</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn" href="/admin/option/">More »</a></p>
            </div>

            <div class="span3">
                <h2>Dashboard users</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
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