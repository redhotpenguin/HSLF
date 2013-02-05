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
        <?php echo $form->labelEx($model, 'payload_id'); ?>
        <?php
        $payloadList = CHtml::listData(Payload::model()->findAll(), 'id', 'title');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );
        echo $form->dropDownList($model, 'payload_id', $payloadList);
        ?>
        <?php echo $form->error($model, 'payload_id'); ?>
    </div>

    <div>
        <?php echo $form->textFieldRow($model, 'creation_date', array('class' => 'span2')); ?>
    </div>

    <hr/>

    <h4>Tags:</h4>

    <div class="row-fluid">
        <?php
        $this->widget('ext.TagSelector.TagSelector', array(
            'model' => $model,
            'tag_types' => array('alert', 'district')
        ));
        ?>
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
