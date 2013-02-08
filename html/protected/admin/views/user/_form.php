<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php
        $userNameOptions = array('size' => 60, 'maxlength' => 128, 'autocomplete' => 'off');

        if (!$model->isNewRecord) {
            $userNameOptions['readonly'] = 'readonly';
        }

        echo $form->labelEx($model, 'username');

        echo $form->textField($model, 'username', $userNameOptions);
        echo $form->error($model, 'username');
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 40, 'maxlength' => 40,  'autocomplete' => 'off')); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'repeat_password'); ?>
        <?php echo $form->passwordField($model, 'repeat_password', array('size' => 60, 'maxlength' => 40,  'autocomplete' => 'off')); ?>
        <?php echo $form->error($model, 'repeat_password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128,  'autocomplete' => 'off')); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <hr/>

    <?php
    
    $this->renderPartial('_tenants', array('model' => $model));
    ?>
    <hr/>
    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>



    <?php $this->endWidget(); ?>

    <div class="text-error">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<b>' . $message . "</b>";
        }
        ?>
    </div>

</div><!-- form -->