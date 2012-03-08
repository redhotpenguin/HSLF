<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-alerts-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state_abbr'); ?>
		<?php echo $form->textField($model,'state_abbr',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'state_abbr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'district_number'); ?>
		<?php echo $form->textField($model,'district_number'); ?>
		<?php echo $form->error($model,'district_number'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->