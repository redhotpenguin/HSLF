<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'app-users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'device_token'); ?>
		<?php echo $form->textField($model,'device_token',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'device_token'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude'); ?>
		<?php echo $form->error($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude'); ?>
		<?php echo $form->error($model,'longitude'); ?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'registration'); ?>
		<?php echo $form->textField($model,'registration'); ?>
		<?php echo $form->error($model,'registration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_agent'); ?>
		<?php echo $form->textField($model,'user_agent',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'user_agent'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->