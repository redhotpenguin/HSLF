<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'push-message-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'tenant_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'share_payload_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'creation_date',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'alert',array('class'=>'span5','maxlength'=>140)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
