<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'district-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'state_id'); ?>
        <?php
        $state_list = CHtml::listData(State::model()->findAll(), 'id', 'name');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );
        echo $form->dropDownList($model, 'state_id', $state_list, $options);
        ?>
        <?php echo $form->error($model, 'state_id'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions(), $options);
        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'number'); ?>
        <?php echo $form->textField($model, 'number'); ?>
        <?php echo $form->error($model, 'number'); ?>
    </div>


    <div class="">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name'); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="">
        <?php echo $form->labelEx($model, 'locality'); ?>
        <?php echo $form->textField($model, 'locality'); ?>
        <?php echo $form->error($model, 'locality'); ?>
    </div>


    <div class=" buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->