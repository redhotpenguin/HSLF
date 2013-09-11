<div class="row-fluid">


    <div class="login-box">
        <div class="status_box">
        <?php
        if (Yii::app()->user->hasFlash('account_settings_success')):
            ?>
                 <div style="width:90%;margin-left:15px;" class="update_box btn-success">
                         <?php echo Yii::app()->user->getFlash('account_settings_success'); ?>
                </div>
                <?php
            endif;

            if (Yii::app()->user->hasFlash('account_settings_error')):
                $flashMessages = Yii::app()->user->getFlashes();
                if ($flashMessages) {
                    echo '<div class="flashes" style="width:90%;margin-left:15px;">';
                    foreach ($flashMessages as $key => $message) {
                        echo '<div class="update_box btn-danger flash-' . $key . '">' . $message . "</div>\n";
                    }
                    echo '</div>';
                }

            endif;
            ?>
        </div>
        <h2>Account settings</h2>
        <h4>Change your password or email address below.</h4>

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

            <div class="input-prepend">
                <?php
                echo $form->labelEx($model, 'username');
                ?>
                <span class="add-on"><i class="halflings-icon user"></i></span>

                <?php
                echo $form->textField($model, 'username', array('autocomplete' => 'off', 'disabled' => 'true', 'class' => 'input-large span10', 'placeholder' => 'username'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="input-prepend">

                <label for="User_password">New Password</label>
                <span class="add-on"><i class="halflings-icon lock"></i></span>
                <?php
                echo $form->passwordField($model, 'password', array('class' => 'input-large span10', 'placeholder' => 'New Password'));
                ?>
            </div>

            <div class="input-prepend">
                <label for="User_repeat_password">Confirm New Password</label>
                <span class="add-on"><i class="halflings-icon lock"></i></span>
                <?php
                echo $form->passwordField($model, 'repeat_password', array('class' => 'input-large span10', 'placeholder' => 'Confirm New Password'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="input-prepend">
                <label for="User_email">Email Address</label>
                <span class="add-on"><i class="halflings-icon email"></i></span>
                <?php
                echo $form->textField($model, 'email', array('class' => 'input-large span10', 'placeholder' => 'Email Address'));
                ?>
            </div>

            <div class="clearfix"></div>

            <div class="prepend button-login">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
            </div>
        </fieldset>

        <?php
        $this->endWidget();

        echo $form->errorSummary($model);
        ?>


    </div>

</div>