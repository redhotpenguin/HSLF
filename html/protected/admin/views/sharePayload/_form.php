<?php
/* @var $this SharePayloadController */
/* @var $model SharePayload */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'share-payload-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('placeholder'=> 'Target URL', 'size'=>60,'maxlength'=>2048, 'class' => 'span11')); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('placeholder'=> 'Share title','size'=>60,'maxlength'=>512, 'class' => 'span11')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('placeholder' => 'Link description for Facebook excerpt and email body','rows'=>6, 'cols'=>50, 'class' => 'span11')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'tweet'); ?>
		<?php echo $form->textArea($model,'tweet',array('placeholder'=> 'Content of the Tweet','rows'=>2,'cols'=>50, 'class' => 'span11')); ?>
		<?php echo $form->error($model,'tweet'); ?>
	</div>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('placeholder' => 'Recipient email address','size'=>60,'maxlength'=>320, 'class' => 'span11')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row-fluid buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->