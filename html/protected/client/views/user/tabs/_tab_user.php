<div class="row">
    <?php
    echo $form->labelEx($model, 'username');
    $usernameOptions = array('size' => 60, 'maxlength' => 128);
    if (!$model->isNewRecord)
        $usernameOptions['readonly'] = 'readonly';
    echo $form->textField($model, 'username', $usernameOptions);
    echo $form->error($model, 'username');
    ?>
</div>

<div class="row">
    <?php
    echo $form->labelEx($model, 'password');
    echo $form->passwordField($model, 'password', array('size' => 40, 'maxlength' => 40));
    echo $form->error($model, 'password');
    ?>
</div>

<div class="">
    <?php
    echo $form->labelEx($model, 'repeat_password');
    echo $form->passwordField($model, 'repeat_password', array('size' => 60, 'maxlength' => 40));
    echo $form->error($model, 'repeat_password');
    ?>
</div>

<div class="row">
    <?php
    echo $form->labelEx($model, 'email');
    echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128));
    echo $form->error($model, 'email');
    ?>
</div>


<div class ="row">
    <legend><i class="icon-warning-sign"></i> Danger Zone </legend>
    <?php
    echo $form->labelEx($model, 'administrator');
    echo CHtml::checkBox('User[administrator]', $model->getAdministrator() );
    echo $form->error($model, 'administrator');
    
    
    ?>

</div>