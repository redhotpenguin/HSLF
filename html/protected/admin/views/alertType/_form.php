<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'alert-type-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'well form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name', array('size' => 60, 'maxlength' => 1024)); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'tag_id');
        $tagList = CHtml::listData(Tag::model()->findAllByAttributes(array('type' => 'alerts')), 'id', 'name');
        echo $form->dropDownList($model, 'tag_id', $tagList);
        echo $form->error($model, 'tag_id');
        ?>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->