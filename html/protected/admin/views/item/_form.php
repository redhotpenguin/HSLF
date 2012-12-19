<?php
if ($model->isNewRecord) {
    

    
    $ns = "var ns  = {site_url: '" . getSetting('site_url') . "',share_url: '" . getTenantSetting('web_app_url') . "' };";
} else {
    $ns = "var ns  = {site_url: '" . getSetting('site_url') . "',share_url: '" . getTenantSetting('web_app_url') . "', item_id: " . $model->id . " };";
}

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/themes/dashboard/js/form/item.js');
?>

<div class="form">
    <?php
    if ($model->id) {
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => true,
            'stateful' => true,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'well form-vertical'),
                ));
    } else {
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => false,
            'stateful' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'well form-vertical'),
                ));
    }


    $recommendation_list = CHtml::listData(Recommendation::model()->findAll(), 'id', 'value');
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>



    <div class="">
        <?php echo $form->labelEx($model, 'item'); ?>
        <?php echo $form->textField($model, 'item', array('class' => 'span7', 'maxlength' => 1000, 'placeholder' => 'i.e Candidate or Measure Name')); ?>
        <?php echo $form->error($model, 'item'); ?>
    </div>

    <div class="left_col">

        <div class="">
            <?php
            echo $form->labelEx($model, 'item_type');
            echo $form->dropDownList($model, 'item_type', $model->getItemTypeOptions());
            echo $form->error($model, 'item_type');
            ?>
        </div>

        <div class="">
            <?php
            $this->widget('ext.DistrictSelector.DistrictSelector', array(
                'model' => $model,
                'attribute' => 'district_id',
                'options' => array(
                    'model_name' => 'Item',
                ),
            ));
            echo $form->error($model, 'district_id');
            ?>
        </div>
    </div>

    <div class="right_col">
        <div class="">
            <?php
            echo $form->labelEx($model, 'party_id');
            echo $form->dropDownList($model, 'party_id', CHtml::listData(Party::model()->findAll(), 'id', 'name'));
            echo $form->error($model, 'party_id');
            ?>
        </div>

        <div class="clearfix">

            <?php
            echo $form->labelEx($model, 'image_url');


            $this->widget('ext.AjaxFileUploader.AjaxFileUploader', array(
                'model' => $model,
                'attribute' => 'image_url',
                'options' => array(
                    'model_name' => 'Item',
                    'upload_handler' => CHtml::normalizeUrl(array('item/upload')),
                    'modal_view' => CHtml::normalizeUrl(array('item/upload')),
                ),
            ))->start();

            echo $form->error($model, 'image_url');
            ?>
        </div>


        <div class="clearfix">
            <?php echo $form->labelEx($model, 'next_election_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Item[next_election_date]',
                'value' => $model->next_election_date,
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'buttonImageOnly' => 'true',
                    'buttonImage' => '/themes/dashboard/img/calendar.png',
                    'showOn' => 'button',
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;float:left;'
                ),
            ));
            ?>
            <?php echo $form->error($model, 'next_election_date'); ?>
        </div>    

        <?php
        echo $form->labelEx($model, 'recommendation_id');
        echo $form->dropDownList($model, 'recommendation_id', $recommendation_list);
        echo $form->error($model, 'recommendation_id');
        ?>


    </div>

    <hr/>

    <div class="left_col">
        <?php
        echo $form->labelEx($model, 'measure_number');
        echo $form->textField($model, 'measure_number', array('size' => 50, 'maxlength' => 24, 'placeholder' => 'Measure Number'));
        echo $form->error($model, 'measure_number');
        ?>
    </div>
    <div class="right_col">
        <?php
        echo $form->labelEx($model, 'friendly_name');
        echo $form->textField($model, 'friendly_name', array('size' => 50, 'maxlength' => 1024, 'placeholder' => 'Friendly Name'));
        echo $form->error($model, 'friendly_name');
        ?>
    </div>

    <div class="clearfix"></div>
    <hr/>

    <div class="">
        <?php
        echo $form->labelEx($model, 'detail');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'detail',
            'htmlOptions' => array(
                'rows' => 20,
                'cols' => 185,
                'class' => 'span9',
            ),
        ));

        echo $form->error($model, 'detail');
        ?>
    </div>

    <hr/>

    <div class="left_col">
        <?php
        echo $form->labelEx($model, 'url');
        echo $form->textField($model, 'url', array('size' => 50, 'maxlength' => 1000, 'placeholder' => 'Microsite URL'));
        ?>
        <a  rel="tooltip" href="#" data-original-title="Microsite link. Click the input field to update it."><i class="icon-question-sign"></i></a>

        <?php
        echo $form->error($model, 'url');
        ?>
        <br/>
        <span id="dynamic_site_url"></span>

        <br/> <br/> 
    </div>

    <div class="right_col">
        <?php
        echo $form->labelEx($model, 'personal_url');
        echo $form->textField($model, 'personal_url', array('size' => 50, 'maxlength' => 2048, 'placeholder' => 'External candidate or measure url'));
        ?>
        <a  rel="tooltip" href="#" data-original-title="Candidate or measure's website."><i class="icon-question-sign"></i></a>

        <?php
        echo $form->error($model, 'personal_url');
        ?>
        <br/>
        External candidate or measure site
    </div>

    <hr/>
    <div class="left_col">
        <?php
        echo $form->labelEx($model, 'facebook_url');
        echo $form->textField($model, 'facebook_url', array('size' => 50, 'maxlength' => 2048, 'placeholder' => 'Facebook URL'));
        ?>
        <?php
        echo $form->error($model, 'facebook_url');
        ?>

        <?php
        echo $form->labelEx($model, 'facebook_share');
        echo $form->textField($model, 'facebook_share', array('size' => 50, 'maxlength' => 1041, 'placeholder' => 'Facebook Share Text'));
        ?>
        <?php
        echo $form->error($model, 'facebook_share');
        ?>
    </div>


    <div class="right_col">
        <?php
        echo $form->labelEx($model, 'twitter_handle');
        echo $form->textField($model, 'twitter_handle', array('size' => 50, 'maxlength' => 16, 'placeholder' => 'Twitter handle'));
        ?>
        <?php
        echo $form->error($model, 'twitter_handle');
        ?>

        <?php
        echo $form->labelEx($model, 'twitter_share');
        echo $form->textField($model, 'twitter_share', array('size' => 50, 'maxlength' => 1041, 'placeholder' => 'Twitter Share Text'));
        ?>
        <?php
        echo $form->error($model, 'twitter_share');
        ?>
    </div>


    <hr/>

    <div class="left_col">
        <?php
        echo $form->labelEx($model, 'election_result_id');
        echo $form->dropDownList($model, 'election_result_id', $recommendation_list);
        echo $form->error($model, 'election_result_id');
        ?>
    </div>

    <div class="right_col">
        <?php
        echo $form->labelEx($model, 'score');

        $this->widget('ext.UIWidgets.SliderWidget', array(
            'model' => $model,
            'attribute' => 'score',
            'options' => array(
                'min' => 0,
                'max' => 101,
                'width' => 200
            ),
        ));

        echo $form->error($model, 'score');
        ?>
    </div>

    <div class="clearfix"></div>

    <hr/>

    <div class="left_col">
        <?php
        $office_list = CHtml::listData(Office::model()->findAll(), 'id', 'name');

        echo $form->labelEx($model, 'office_id');
        echo $form->dropDownList($model, 'office_id', $office_list);
        echo $form->error($model, 'office_id');
        ?>
    </div>

    <div class="right_col">
        <?php
        echo $form->labelEx($model, 'hold_office');
        echo $form->dropDownList($model, 'hold_office', array('na' => 'Not Applicable', 'no' => 'No', 'yes' => 'Yes',));
        echo $form->error($model, 'hold_office');
        ?>
    </div>



    <hr/>

    <?php
    echo $this->renderPartial('_endorser', array(
        'model' => $model,
        'endorser_list' => $endorser_list
    ));
    ?>

    <?php
    if (ScorecardItem::model()->count() > 0):
        ?>

        <br/>
        <label>Scorecard</label>
        <span id="scorecard_spin" class="ajax_wheel_spin"><img src="/themes/dashboard/img/64_orange_wheel.gif"/></span>
        <div id ="dynamic_scorecard_table"> 
            <?php
            //  endif;
            ?>
        </div>
        <?php
    endif;
    ?>


    <hr/>

    <div class="">
        <?php echo $form->labelEx($model, 'keywords'); ?>
        <?php echo $form->textField($model, 'keywords', array('class' => 'span7', 'placeholder'=>'Revenue schools education teachers students  class size')); ?>
        <?php echo $form->error($model, 'keywords'); ?>
    </div>


    <hr/>
    <div class="left_col">
        <?php echo $form->labelEx($model, 'date_published'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Item[date_published]',
            'value' => $model->date_published,
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd ' . date('h:i:s'),
                'buttonImageOnly' => 'true',
                'buttonImage' => '/themes/dashboard/img/calendar.png',
                'showOn' => 'button',
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;float:left;'
            ),
        ));
        ?>
        <?php echo $form->error($model, 'date_published'); ?>
    </div>

    <div class="right_col ">
        <?php echo $form->labelEx($model, 'published'); ?>
        <?php echo $form->dropDownList($model, 'published', array('yes' => 'Yes', 'no' => 'No')); ?>
        <?php echo $form->error($model, 'published'); ?>
    </div>

    <div class="clearfix"></div>

    <?php
    if (!$model->isNewRecord) {

        $url = CHtml::normalizeUrl(array(
                    'item/update',
                    'id' => $model->id,
                    'enctype' => 'multipart/form-data',
                ));

        // CHtml::submitButton('Create');
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
               target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                 
                  if ( response == "success" ){
                         sessionStorage.setItem("ItemContent", "");
                         target.addClass("btn-success");
                         target.html( "item saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save item." );
                  }
                target.fadeOut(5000, function(){
                 target.removeClass("btn-danger");
                 target.removeClass("btn-success");
                });
              
                
             }',
            'error' => 'js:function(object){
              
                target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                   target.addClass("btn-danger");
         
                   target.html( "Could not save item:<br/>" + object.responseText );
             
                //target.fadeOut(5000, function(){
                // target.removeClass("btn-danger");
              //  });
            }',
        ));
    }else
        echo CHtml::submitButton('Create');
    ?> 

    <div class="hidden update_box" id="targetdiv"></div>

    <?php $this->endWidget(); ?>

    <br/>
    <h1>News updates:</h1>

    <?php
    if ($model->id):
        $new_item_news_url = CHtml::normalizeUrl(array('itemNews/add', 'item_id' => $model->id));

        echo CHtml::link('Add a news update', $new_item_news_url, array('target' => '_blank'));
        ?>
        <br/>
        <br/>

        <?php
        if ($model->itemNews):

            foreach ($model->itemNews as $itemNew) {
                ?>

                <div class="news_item">
                    <span class="btn floatright">


                        <?php
                        $edit_item_news_url = CHtml::normalizeUrl(array('itemNews/update', 'id' => $itemNew->id));
                        echo CHtml::link('Edit', $edit_item_news_url, array('target' => '_blank'));
                        ?>

                    </span>

                    <b> <?php echo $itemNew->title; ?>:</b>
                    <br/>
                    <p><?php echo $itemNew->getExcerpt() ?></p>

                    <?php ?> 
                </div>
                <?php
            }

        else:
            echo 'No news updates';
        endif;

    else:
        echo 'You must save an item before you can add a news.';
    endif; // end test $model->itemNews
    ?>


</div><!-- form -->