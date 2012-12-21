<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'recommendation-form',
	'enableAjaxValidation'=>false,
            'htmlOptions' => array('class' => 'form-vertical'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="">
		<?php echo $form->labelEx($model,'type');
                 echo $form->dropDownList($model, 'type', $model->getTypeOptions());
		 echo $form->error($model,'type'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->