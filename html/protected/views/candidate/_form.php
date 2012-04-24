<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'candidate-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'full_name'); ?>
        <?php echo $form->textField($model, 'full_name', array('size' => 60, 'maxlength' => 256)); ?>
        <?php echo $form->error($model, 'full_name'); ?>
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
                'url' => CController::createUrl('district/dynamicdistrict?model=Candidate'), //url to call.  
                'update' => '#Candidate_district_id', //selector to update      
            )
        );
        echo $form->dropDownList($model, 'state_abbr', $state_list, $options);
        ?>
        <?php echo $form->error($model, 'state_abbr'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'district_id');

        echo $form->dropDownList($model, 'district_id', District::getTagDistrictsByState($model->state_abbr)
        );
        ?>

        <?php echo $form->error($model, 'district'); ?>
    </div>


    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions(), $options);
        echo $form->error($model, 'type');
        ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'party'); ?>
        <?php echo $form->textField($model, 'party', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'party'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'scorecard'); ?>
        <?php echo $form->textField($model, 'scorecard', array('size' => 60, 'maxlength' => 256)); ?>
        <?php echo $form->error($model, 'scorecard'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'date_published'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Candidate[date_published]',
            'value' => $model->date_published,
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
        <?php echo $form->error($model, 'date_published'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'endorsement');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'endorsement',
            'htmlOptions' => array(
                'rows' => 30,
                'cols' => 80,
            ),
        ));

        echo $form->error($model, 'endorsement');
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'publish'); ?>
        <?php echo $form->dropDownList($model, 'publish', array('yes' => 'Yes', 'no' => 'No')); ?>
        <?php echo $form->error($model, 'publish'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->



<?php
$fields = array(
    'fields' => array(
        array('Candidate_full_name'),
        array('Candidate_state_abbr', 'to_abbr'),
        array('Candidate_district_id', 'to_district'),
        array('Candidate_scorecard', 'color_code'),
        array('Candidate_endorsement'),
    ),
);

$this->widget('ext.FormPreview.FormPreview', array('fields' => $fields, 'form_id' => 'candidate-form'));
?>

<script type="text/javascript">
    // filters
    function to_upper_case(data){
        data = remove_not_set(data);
        return data.toUpperCase();
    }
    
    function to_abbr(data){
        if(data.toLowerCase() == '(not set)')
            return ''
        
        data = data.substr(0,2);
        data = '(' + data +'-';
        data = data.toUpperCase();
        return data;
    }
    
    function to_district(data){
        return data+')';
    }
    
    
    function color_code(score){
        var style='';
        if(score<20){
            style = 'color:red;';
        }
        else if(score<50){
            style = 'color:blue;';
        }
        else{
            style = 'color:green';
        }

        return '<span style="'+style+'">'+score+'</span>';
    }

    // cledit doesnt play nice with formpreview, simulate keyup
    function force_refresh() {
        $('#Candidate_endorsement').keyup();
        $('#Candidate_date_published').keyup();
        $('#Candidate_district_id').change();
    }
    var check_form_result = setInterval(force_refresh, 300);

</script>


<div id="candidate_preview">
    <div id="iphone_bg">
        <div id="iphone_header">   
            <img class="iphone_back_btn" src='/themes/hslf/css/iphone_back_btn.png'/>
            <div id="Candidate_full_name_preview" class="input_preview"></div>

     
        
        <span id="iphone_state_district">
             <label id="Candidate_state_abbr_preview" class="list_preview"></label>
             <label id="Candidate_district_id_preview" class="list_preview"></span>
        </span>
            
        </div>
        
        
        
        <div id="Candidate_scorecard_preview" class="input_preview"></div>
        <div id="Candidate_endorsement_preview" class="input_preview"></div>
    </div>
</div>