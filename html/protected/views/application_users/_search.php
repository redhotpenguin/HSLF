<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'device_token'); ?>
		<?php echo $form->textField($model,'device_token',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'latitude'); ?>
		<?php echo $form->textField($model,'latitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state_abbr'); ?>
		<?php echo $form->textField($model,'state_abbr',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'district_number'); ?>
		<?php echo $form->textField($model,'district_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'registration'); ?>
		<?php echo $form->textField($model,'registration'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_agent'); ?>
		<?php echo $form->textField($model,'user_agent',array('size'=>60,'maxlength'=>1024)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->