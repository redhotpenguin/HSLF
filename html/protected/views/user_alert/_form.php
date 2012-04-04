<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-alert-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 1024)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>


    <div class="row">
        <?php
        echo $form->labelEx($model, 'content');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'content',
            'htmlOptions' => array(
                'rows' => 30,
                'cols' => 80,
            ),
        ));

        echo $form->error($model, 'content');
        ?>
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
                'url' => CController::createUrl('district/dynamicdistrict?model=User_alert'), //url to call.  
                'update' => '#User_alert_district_id', //selector to update      
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

        <?php echo $form->error($model, 'district'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'create_time'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'User_alert[create_time]',
            'value' => $model->create_time,
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
        <?php echo $form->error($model, 'create_time'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->



<?php
$fields = array(
    'fields' => array(
        array('User_alert_title'),
        array('User_alert_content'),
        array('User_alert_state_abbr', 'remove_not_set'),
        array('User_alert_district_id', 'remove_not_set'),
        array('User_alert_create_time')
    ),
);

$this->widget('ext.FormPreview.FormPreview', array('fields' => $fields, 'form_id' => 'user-alert-form'));
?>

<script type="text/javascript">

    function remove_not_set(data){
        if(data.toLowerCase() == '(not set)')
            return ''
        
        return data;
    }

    function force_refresh() {
        $('#User_alert_content').keyup();
        $('#User_alert_district_id').change();
        $('#User_alert_create_time').keyup();
    }
    var check_form_result = setInterval(force_refresh, 300);

</script>

<div id="user_alert_preview">
    <div id="iphone_bg">
        <div id="User_alert_title_preview" class="input_preview"></div>
        <div id="User_alert_content_preview" class="input_preview"></div>

        <div id="User_alert_state_abbr_preview" class="input_preview"></div>
        <div id="User_alert_district_id_preview" class="input_preview"></div>
        <div id="User_alert_create_time_preview" class="input_preview"></div>
    </div>
</div>