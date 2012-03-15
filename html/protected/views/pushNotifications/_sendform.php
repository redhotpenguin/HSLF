<div class="form">

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'push-notifications-form',
	'enableAjaxValidation'=>false,
       // 'action'=>'notificationSent?id='.$model->id,
        
)); 

?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>
        
    <?php 
    $options= array('id'=>'district_ids');
    
    $this->widget('ext.DistrictTreeView.DistrictTreeView', array( 'options'=>$options ));
   
            ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Send'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->