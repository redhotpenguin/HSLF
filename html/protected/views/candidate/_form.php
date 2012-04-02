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

        echo $form->dropDownList($model, 'district_id', CHtml::listData(
                        District::model()->findAllByAttributes(
                                array('state_abbr' => $model->state_abbr)
                        ), 'id', 'number')
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

        $this->widget('ext.TinyMce.TinyMce', array(
            'model' => $model,
            'attribute' => 'endorsement'
        ));

        echo $form->error($model, 'endorsement');
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'publish'); ?>
        <?php echo $form->dropDownList($model, 'publish', array('yes' => 'Yes', 'no' => 'No'), $options); ?>
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
        array('Candidate_state_abbr'),
        array('Candidate_district_id'),
        array('Candidate_type', 'to_upper_case'),
        array('Candidate_party'),
        array('Candidate_scorecard'),
        array('Candidate_date_published'),
        array('Candidate_endorsement'),
    ),
);

$this->widget('ext.FormPreview.FormPreview', array('fields' => $fields, 'form_id' => 'candidate-form'));
?>

<script type="text/javascript">
    // filters
    function to_upper_case(data){
        return data.toUpperCase();
    }

    // cledit doesnt play nice with formpreview, simulate keyup
    function force_refresh() {
        $('#Candidate_endorsement').keyup();
        $('#Candidate_date_published').keyup();
        $('#Candidate_district_id').change();
    }
    var check_form_result = setInterval(force_refresh, 1000);

</script>


<div id="candidate_preview">
    <div id="iphone_bg">
        <div id="Candidate_full_name_preview" class="input_preview"></div>
        <div id="Candidate_state_abbr_preview" class="list_preview"></div>
        <div id="Candidate_district_id_preview" class="list_preview"></div>
        <div id="Candidate_type_preview" class="list_preview"></div>
        <div id="Candidate_party_preview" class="list_preview"></div>
        <div id="Candidate_scorecard_preview" class="input_preview"></div>
        <div id="Candidate_date_published_preview" class="input_preview"></div>
        <div id="Candidate_endorsement_preview" class="input_preview"></div>
    </div>
</div>