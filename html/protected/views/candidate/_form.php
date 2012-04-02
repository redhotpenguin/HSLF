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

        $this->widget('application.extensions.cleditor.ECLEditor', array(
            'model' => $model,
            'attribute' => 'endorsement', //Model attribute name. Nome do atributo do modelo.
            'options' => array(
                'width' => '650',
                'height' => 350,
                'useCSS' => true
            ),
            'value' => $model->endorsement, //If you want pass a value for the widget. I think you will. Se você precisar passar um valor para o gadget. Eu acho irá.
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
$previewOptions = array(
    'fields' => array('Candidate_full_name',
        'Candidate_state_abbr',
        'Candidate_district_id',
        'Candidate_type',
        'Candidate_party',
        'Candidate_scorecard',
        'Candidate_date_published',
        'Candidate_endorsement',
    ),
);
$this->widget('ext.FormPreview.FormPreview', array('options' => $previewOptions));
?>

<div id="candidate_preview">
    <div id="Candidate_full_name_preview" class="input_preview"></div>
    <div id="Candidate_state_abbr_preview" class="list_preview"></div>
    <div id="Candidate_district_id_preview" class="list_preview"></div>
    <div id="Candidate_type_preview" class="list_preview"></div>
    <div id="Candidate_party_preview" class="list_preview"></div>
    <div id="Candidate_scorecard_preview" class="input_preview"></div>
    <div id="Candidate_date_published_preview" class="input_preview"></div>
    <div id="Candidate_endorsement_preview" class="input_preview"></div>
</div>