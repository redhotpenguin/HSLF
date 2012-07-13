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
        <?php
        $this->widget('ext.DistrictSelector.DistrictSelector', array(
            'model' => $model,
            'attribute' => 'district_id',
            'options' => array(
                'model_name' => 'Candidate',
            ),
        ));
        echo $form->error($model, 'district_id');
        ?>
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
        <?php echo $form->labelEx($model, 'url'); ?>
        <?php echo $form->textField($model, 'url', array('size' => 60)); ?>
        <?php echo $form->error($model, 'url'); ?>
    </div>


    <?php
    echo $form->hiddenField($model, 'date_published', array('value' => date('Y-m-d H:i:s')));
    ?>


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
            if (data=='')
                return;
            
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
        <div id="iphone_wrapper">
            <div id="iphone_bg">
                <div id="iphone_header">   
                    <img class="iphone_back_btn" src='/themes/dashboard/css/iphone_back_btn.png'/>
                    <div id="Candidate_full_name_preview" class="input_preview"></div>



                    <span id="iphone_state_district">
                        <span id="Candidate_state_abbr_preview" class="list_preview"></span>
                        <span id="Candidate_district_id_preview" class="list_preview"></span>
                    </span>

                </div>



                <div id="Candidate_scorecard_preview" class="input_preview"></div>
                <div id="Candidate_endorsement_preview" class="input_preview"></div>
            </div>
        </div>
    </div>


    <br/>
    <h1>Candidate Issues:</h1>

    <?php
    $candidateIssueFormConfig = array(
        'elements' => array(
            'name' => array(
                'type' => 'dropdownlist',
                'items' => array(//todo: move the following values to the candidate_issue model
                    '' => '-',
                    'Puppy Mills Cosponsor' => 'Puppy Mills Cosponsor',
                    'Chimps in Labs Cosponsor' => 'Chimps in Labs Cosponsor',
                    'Animal Fighting Cosponsor' => 'Animal Fighting Cosponsor',
                    'Horse Slaughter Cosponsor' => 'Horse Slaughter Cosponsor',
                    'Ag Subsidies Vote' => 'Ag Subsidies Vote',
                    'Lethal Predator Control Vote' => 'Lethal Predator Control Vote',
                    'ESA Vote' => 'ESA Vote',
                    'Funding Letter' => 'Funding Letter',
                    'Leaders' => 'Leaders',
                ),
            ),
            'value' => array(
                'type' => 'dropdownlist',
                'items' => array('' => '-', 'yes' => 'Yes', 'no' => 'No'),
            ),
            'detail' => array(
                'type' => 'textarea',
                'rows' => 5,
                'cols' => 80,
            ),
            ));

    $this->widget('ext.multimodelform.MultiModelForm', array(
        'id' => 'id_candidate_issue',
        'formConfig' => $candidateIssueFormConfig,
        'addItemText' => 'Add an issue',
        'model' => $candidate_issue,
        'validatedItems' => $validate_candidate_issues,
        'data' => empty($validatedItems) ? $candidate_issue->findAll(
                        array('condition' => 'candidate_id=:candidate_id',
                            'params' => array(':candidate_id' => $model->id),
                            'order' => 'id',
                )) : null,
        'sortAttribute' => '',
        'showAddItemOnError' => false,
        'fieldsetWrapper' => array('tag' => 'div',
            'htmlOptions' => array('class' => 'candidate_issue_item')
        ),
        'removeLinkWrapper' => array('tag' => 'div',
            'htmlOptions' => array('style' => 'position:absolute; top:1em; right:1em;')
        ),
    ));
    ?>



    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>
</div><!-- form -->