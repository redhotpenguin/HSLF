<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
  $cs->registerScriptFile($baseUrl.'/js/form/push.js');
?>


<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'push-notifications-form',
        'enableAjaxValidation' => false,
            // 'action'=>'notificationSent?id='.$model->id,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'message'); ?>
        <?php echo $form->textArea($model, 'message', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'message'); ?>
    </div>


    <div class="row">
        <h3>Audience:</h3>
        <?php
        $options = array(
            'empty' => '(not set)',
            'tabindex' => '0',
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => CController::createUrl('district/GetTreeView'), //url to call.  
                'update' => '#audience_target', //selector to update      
            )
        );
        
        $htmlOptions = array(
            'template'=> '{label} {input}  ',
            'separator'=> '<br/>',
            'labelOptions' => array('class'=> 'audience_type_label'),
        );
        echo CHtml::radioButtonList('audience_type', 'district', array('district' => 'District', 'broadcast' => 'Broadcast'), $htmlOptions);
        
        ?>
        
        <div id="audience_target" style="border:1px solid #ccc; padding:5px;">
            
        </div>
    
    </div>

    <div class="row buttons">
        <?php
        $controllerAjaxUrl = CController::createUrl('pushNotifications/UpdateAjax', array('id' => $model->id));
        echo CHtml::ajaxButton("Send Notification", $controllerAjaxUrl, array(
            'update' => '#notificationResult',
            'type' => 'POST',
            'beforeSend' => 'function(){ $("#ajaxLoadingIcon").addClass("loading");}',
            'complete' => 'function(e){  $("#ajaxLoadingIcon").removeClass("loading");}',
        ));
        ?>
    </div>

    <div id ="ajaxLoadingIcon"> </div>

    <div id="notificationResult">
        <?php $this->renderPartial('_ajaxPushResultContent', array('pushNotificationResult' => $data['pushNotificationResult'])); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

