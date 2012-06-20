<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'district-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'state_abbr'); ?>
        <?php
        $state_list = CHtml::listData(State::model()->findAll(), 'abbr', 'name');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );
        echo $form->dropDownList($model, 'state_abbr', $state_list, $options);
        ?>
        <?php echo $form->error($model, 'state_abbr'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions(), $options);
        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="row">
<?php echo $form->labelEx($model, 'number'); ?>
<?php echo $form->textField($model, 'number'); ?>
<?php echo $form->error($model, 'number'); ?>
    </div>



    <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->