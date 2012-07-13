<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'application-users-form',
        'enableAjaxValidation' => false,
            'htmlOptions' => array('class' => 'well form-vertical'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'device_token'); ?>
<?php echo $form->textField($model, 'device_token', array('size' => 60, 'maxlength' => 128 ) ); ?>
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
            <?php
            //  echo $form->labelEx($model, 'district_id');
            $this->widget('ext.DistrictSelector.DistrictSelector', array(
                'model' => $model,
                'attribute' => 'district_id',
                'options' => array(
                    'model_name' => 'BallotItem',
                ),
            ));
            echo $form->error($model, 'district_id');
            ?>
        </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'registration'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Application_user[registration]',
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
        <?php echo $form->textField($model, 'type', array('rows' => 6, 'cols' => 50, 'readonly'=>'readonly')); ?>
<?php echo $form->error($model, 'type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'user_agent'); ?>
        <?php echo $form->textField($model, 'user_agent', array('size' => 60, 'maxlength' => 1024)); ?>
<?php echo $form->error($model, 'user_agent'); ?>
    </div>
    
        <div class="row">
        <?php echo $form->labelEx($model, 'uap_user_id'); ?>
        <?php echo $form->textField($model, 'uap_user_id', array('size' => 60, 'maxlength' => 1024)); ?>
<?php echo $form->error($model, 'uap_user_id'); ?>
    </div>

    <div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
