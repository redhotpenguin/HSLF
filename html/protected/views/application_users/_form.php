<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'application-users-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'device_token'); ?>
<?php echo $form->textField($model, 'device_token', array('size' => 60, 'maxlength' => 128)); ?>
<?php echo $form->error($model, 'device_token'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'latitude'); ?>
<?php echo $form->textField($model, 'latitude'); ?>
<?php echo $form->error($model, 'latitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'longitude'); ?>
<?php echo $form->textField($model, 'longitude'); ?>
<?php echo $form->error($model, 'longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'state_abbr'); ?>
        <?php
        $state_list = CHtml::listData(State::model()->findAll(), 'abbr', 'name');

        $options = array(
            'empty' => '(not set)',
            'tabindex' => '0',
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => CController::createUrl('district/dynamicdistrict?model=Application_users'), //url to call.  
                'update' => '#Application_users_district_id', //selector to update      
            )
        );
        echo $form->dropDownList($model, 'state_abbr', $state_list, $options);
        ?>
        <?php echo $form->error($model, 'state_abbr'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'district_id');

        echo $form->dropDownList($model, 'district_id', CHtml::listData(
                        District::model()->findAllByAttributes(
                                array('state_abbr' => $model->state_abbr)
                        ), 'id', 'number')
        );
        ?>

<?php echo $form->error($model, 'district_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'registration'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Application_users[registration]',
            'value' => $model->registration,
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd ' . date('h:i:s'),
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;'
            ),
        ));
        ?>


<?php echo $form->error($model, 'registration'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->textArea($model, 'type', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'user_agent'); ?>
        <?php echo $form->textField($model, 'user_agent', array('size' => 60, 'maxlength' => 1024)); ?>
<?php echo $form->error($model, 'user_agent'); ?>
    </div>

    <div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
