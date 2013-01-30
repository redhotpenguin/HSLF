<?php
 $tenant = Yii::app()->user->getCurrentTenant();
 
 $siteUrl = $siteUrl = getSetting('site_url').'/admin/'.$tenant->name;
 
if ($model->isNewRecord) {
    $ns = "var ns  = {site_url: '" . $siteUrl . "',share_url: '" . $tenant->web_app_url . "' };";
} else {
    $ns = "var ns  = {site_url: '" . $siteUrl . "',share_url: '" . $tenant->web_app_url . "', item_id: " . $model->id . " };";
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
                'class' => 'form-vertical'),
                ));
    } else {
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => false,
            'stateful' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'form-vertical'),
                ));
    }


    $recommendation_list = CHtml::listData(Recommendation::model()->findAll(), 'id', 'value');
    ?>

    <?php echo $form->errorSummary($model); ?>

    <h4>Basic info:</h4>
    <div class="row-fluid">


        <div class="span8">
            <?php echo $form->labelEx($model, 'item'); ?>
            <?php echo $form->textField($model, 'item', array('class' => 'span11', 'maxlength' => 1000, 'placeholder' => 'i.e Candidate or Measure\'s name')); ?>
            <?php echo $form->error($model, 'item'); ?>
        </div>

        <div class="span4">
            <?php
            echo $form->labelEx($model, 'item_type');
            echo $form->dropDownList($model, 'item_type', $model->getItemTypeOptions());
            echo $form->error($model, 'item_type');
            ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <hr/>

    <h4>Location info:</h4>
    <div class="row-fluid">
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

    <hr/>

    <div  id="candidate_related_inputs" >
        <h4>Candidate info:</h4>
        <div class="row-fluid">
            <div class="span6">
                <?php
                echo $form->labelEx($model, 'first_name');
                echo $form->textField($model, 'first_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => ''));
                echo $form->error($model, 'first_name');
                ?>
            </div>
            <div class="span6">
                <?php
                echo $form->labelEx($model, 'last_name');
                echo $form->textField($model, 'last_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => ''));
                echo $form->error($model, 'last_name');
                ?>
            </div>
        </div>
    </div>


    <div id ="measure_related_inputs">
        <h4>Measure info:</h4>
        <div class="row-fluid">
            <div class="span6">
                <?php
                echo $form->labelEx($model, 'measure_number');
                echo $form->textField($model, 'measure_number', array('class' => 'span12', 'maxlength' => 24, 'placeholder' => 'Measure Number'));
                echo $form->error($model, 'measure_number');
                ?>
            </div>
            <div class="span6">
                <?php
                echo $form->labelEx($model, 'friendly_name');
                echo $form->textField($model, 'friendly_name', array('class' => 'span12', 'maxlength' => 1024, 'placeholder' => 'Friendly Name'));
                echo $form->error($model, 'friendly_name');
                ?>
            </div>
        </div>
    </div>



    <hr/>

    <h4>Extra info:</h4>
    <div class="row-fluid">
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'party_id');
            echo $form->dropDownList($model, 'party_id', CHtml::listData(Party::model()->findAll(), 'id', 'name'), array('class' => 'span12'));
            echo $form->error($model, 'party_id');
            ?>
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model, 'next_election_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Item[next_election_date]',
                'value' => $model->next_election_date,
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;',
                    'class' => 'span12'
                ),
            ));
            ?>
            <?php echo $form->error($model, 'next_election_date'); ?>
        </div>   
    </div>
    <br/>
    <div class="row-fluid">

        <div class="span6">
            <?php
            echo $form->labelEx($model, 'recommendation_id');
            echo $form->dropDownList($model, 'recommendation_id', $recommendation_list, array('class' => 'span12'));
            echo $form->error($model, 'recommendation_id');
            ?>
        </div>

        <div class="span6">
            <?php
            echo $form->labelEx($model, 'image_url');


            $this->widget('ext.AjaxFileUploader.AjaxFileUploader', array(
                'model' => $model,
                'attribute' => 'image_url',
                'options' => array(
                    'model_name' => 'Item',
                    'upload_handler' => CHtml::normalizeUrl(array('item/upload')),
                    'modal_view' => CHtml::normalizeUrl(array('item/upload')),
                    'class' => 'span12'
                ),
            ))->start();

            echo $form->error($model, 'image_url');
            ?>
        </div>

    </div>
    <br/>
    <div>
        <?php
        echo $form->labelEx($model, 'detail');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'detail',
            'htmlOptions' => array(
                'rows' => 20,
                'class' => 'span9',
            ),
        ));

        echo $form->error($model, 'detail');
        ?>
    </div>

    <hr/>

    <h4>Social info:</h4>
    <div class="row-fluid">
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'facebook_url');
            echo $form->textField($model, 'facebook_url', array('class' => 'span12', 'maxlength' => 2048, 'placeholder' => 'Facebook URL'));
            ?>
            <?php
            echo $form->error($model, 'facebook_url');
            ?>
        </div>

        <div class="span6">
            <?php
            echo $form->labelEx($model, 'twitter_handle');
            echo $form->textField($model, 'twitter_handle', array('class' => 'span12', 'maxlength' => 16, 'placeholder' => 'Twitter handle'));
            ?>
            <?php
            echo $form->error($model, 'twitter_handle');
            ?>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span6">
            <?php
            echo $form->labelEx($model, 'slug');
            echo $form->textField($model, 'slug', array('class' => 'span12', 'maxlength' => 1000, 'placeholder' => 'Web app. slug'));
            ?>

            <?php
            echo $form->error($model, 'slug');
            ?>
            <br/>
            <span id="dynamic_site_url"></span>

        </div>

        <div class="span6">
            <?php
            echo $form->labelEx($model, 'website');
            echo $form->textField($model, 'website', array('class' => 'span12', 'maxlength' => 2048, 'placeholder' => 'Candidate or measure\'s website'));
            ?>

            <?php
            echo $form->error($model, 'website');
            ?>
        </div>
    </div>




    <div class="clearfix"></div>

    <hr/>

    <?php
    echo $this->renderPartial('_organization', array(
        'model' => $model,
        'organization_list' => $organization_list
    ));
    ?>

    <hr/>
    <div>
        <h4>Publication info:</h4>
        <div class="row-fluid">
            <div class="span6">
                <?php echo $form->labelEx($model, 'date_published'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'Item[date_published]',
                    'value' => $model->date_published,
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd ' . date('h:i:s'),
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
                        'class' => 'span12'
                    ),
                ));
                ?>
                <?php echo $form->error($model, 'date_published'); ?>
            </div>


            <div class="span6">
                <?php echo $form->labelEx($model, 'published'); ?>
                <?php echo $form->dropDownList($model, 'published', array('yes' => 'Yes', 'no' => 'No')); ?>
                <?php echo $form->error($model, 'published'); ?>
            </div>

        </div>
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
                         target.html( "Ballot item saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save ballot item." );
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