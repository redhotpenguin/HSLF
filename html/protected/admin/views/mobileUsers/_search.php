<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'_id'); ?>
		<?php echo $form->textField($model,'_id'); ?>
	</div>
    
    	<div class="row">
		<?php echo $form->label($model,'device_identifier'); ?>
		<?php echo $form->textField($model,'device_identifier',array('size'=>60,'maxlength'=>256)); ?>
	</div>
    
         <div class="row">
		<?php echo $form->label($model,'device_identifier'); ?>
		<?php echo $form->textField($model,'device_identifier',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'device_type'); ?>
		<?php echo $form->textField($model,'device_type',array('size'=>60,'maxlength'=>256)); ?>
	</div>
    
    <div class="row">
		<?php echo $form->label($model,'districts'); ?>
		<?php echo $form->textField($model,'districts',array('size'=>60,'maxlength'=>256)); ?>
	</div>
    
       <div class="row">
		<?php echo $form->label($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->