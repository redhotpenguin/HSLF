<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tag-form',
        'enableAjaxValidation' => true,
        'stateful' => true,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTagTypes());

        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="row buttons">
    
        <?php
        if (!$model->isNewRecord) {

            $url = CHtml::normalizeUrl(array(
                'tag/update',
                'id'=> $model->id,
            ));
            
            echo CHtml::ajaxSubmitButton('Save', $this->createUrl($url), array(
                'type' => 'POST',
                'update' => '#targetdiv',
                'beforeSend' => 'js:function(){
                    target =$("#targetdiv");
                    target.fadeIn();
                    target.removeClass("hidden");
                    target.addClass("btn-info");
                    target.html("saving...");
                 }',
                'success' => 'js:function(response) {
                  if ( response != "success" )
                      return false;

                  target =$("#targetdiv");
            
                 target.removeClass("btn-info");
                target.fadeIn();
                target.removeClass("hidden");
                target.html( "tag saved" );
                target.fadeOut(5000);
             }',
            ));
        }else
            echo CHtml::submitButton('Create');
        ?> 


    </div>

    <div class="hidden update_box" id="targetdiv">
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->