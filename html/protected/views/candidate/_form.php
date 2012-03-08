<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'candidate-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	

	<div class="row">
		<?php echo $form->labelEx($model,'state_abbr'); ?>
		<?php $state_list = CHtml::listData(State::model()->findAll(), 'abbr', 'name');
                   
                $options = array(
                     'empty' => '(not set)',
                         'tabindex' => '0',
                         'ajax' => array(
                         'type'=>'POST', //request type
                         'url'=>CController::createUrl('candidate/dynamicdistrict'), //url to call.  
                         'update'=>'#Candidate_district_number', //selector to update      
                        )
                    );
                    echo $form->dropDownList($model,'state_abbr', $state_list, $options); 
                 ?>
		<?php echo $form->error($model,'state_abbr'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'district_number'); ?>
		<?php 
        echo $form->dropDownList($model,'district_number',  CHtml::listData( District::model()->findAllByAttributes( array('state_abbr'=> $model->state_abbr )), 'number', 'number' ) ) ;

                ?>
		<?php echo $form->error($model,'district_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php 
                  $options = array(
                         'tabindex' => '0',
                          'empty' => '(not set)',
                    );
               
                 echo $form->dropDownList($model,'type', array('senator'=>'Senator', 'representative'=>'Representative') , $options); 
                ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endorsement'); ?>
		<?php echo $form->textArea($model,'endorsement',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'endorsement'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'full_name'); ?>
		<?php echo $form->textField($model,'full_name',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'full_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'party'); ?>
		<?php echo $form->textField($model,'party',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'party'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_published'); ?>
                <?php
                   $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'Candidate[date_published]',
                    'value'=>$model->date_published,

                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'dateFormat'=>'yy-mm-dd '.date('h:i:s'),
                    ),
                    'htmlOptions'=>array(
                        'style'=>'height:20px;'
                    ),
                ));
                ?>
		<?php echo $form->error($model,'date_published'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'publish'); ?>
		<?php  echo $form->dropDownList($model,'publish', array('yes'=>'Yes', 'no'=>'No') , $options);  ?>
		<?php echo $form->error($model,'publish'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->