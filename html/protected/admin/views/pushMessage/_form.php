<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'push-message-form',
    'enableAjaxValidation' => false,
        ));
?>
<div class="form-actions">

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div>  

        <?php echo $form->textAreaRow($model, 'alert', array('class' => 'span5', 'cols' => '150', 'rows' => '2')); ?>
    </div>

    <div>
        <?php echo $form->textFieldRow($model, 'share_payload_id', array('class' => 'span1')); ?>
    </div>

    <div>
        <?php echo $form->textFieldRow($model, 'creation_date', array('class' => 'span2')); ?>
    </div>


    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
