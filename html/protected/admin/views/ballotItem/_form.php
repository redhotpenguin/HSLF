<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ballot-item-form',
        'enableAjaxValidation' => true,
        'stateful' => true,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
            'class' => 'well form-vertical'),
            ));
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
                    'model_name' => 'BallotItem',
                ),
            ));
            echo $form->error($model, 'district_id');
            ?>


        </div>

    </div>

    <div class="right_col">
        <div class="">
            <?php
            echo $form->labelEx($model, 'party');
            echo $form->dropDownList($model, 'party', $model->getParties());
            echo $form->error($model, 'party');
            ?>
        </div>

        <div class="">

            <?php
            echo $form->labelEx($model, 'image_url');

            
            $this->widget('ext.AjaxFileUploader.AjaxFileUploader', array(
                'model' => $model,
                'attribute' => 'image_url',
                'options' => array(
                    'model_name' => 'BallotItem',
                    'upload_handler' => CHtml::normalizeUrl(array('ballotItem/upload')),
                    'modal_view' => CHtml::normalizeUrl(array('ballotItem/upload')),
                ),
            ))->start();

            echo $form->error($model, 'image_url');
            ?>

        </div>

        <div class="">
            <?php echo $form->labelEx($model, 'next_election_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'BallotItem[next_election_date]',
                'value' => $model->next_election_date,
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'buttonImageOnly' => 'true',
                    'buttonImage' => '/themes/hslf/img/calendar.png',
                    'showOn' => 'button',
                ),
                'htmlOptions' => array(
                    'style' => 'height:20px;float:left;'
                ),
            ));
            ?>
            <?php echo $form->error($model, 'next_election_date'); ?>
        </div>    
        <div style="clear:both;"></div>
        <div class="">
            <?php
            echo $form->labelEx($model, 'office_type');
            echo $form->dropDownList($model, 'office_type', $model->getOfficeTypes());
            echo $form->error($model, 'office_type');
            ?>
        </div>


    </div>

    <hr/>

    <div class="">
        <?php
        echo $form->labelEx($model, 'priority');
        echo $form->dropDownList($model, 'priority', $model->getPriorityOptions());
        echo $form->error($model, 'priority');
        ?>
        <p> 1 = Lowest, 10 = highest</p>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'recommendation_id');
        echo $form->dropDownList($model, 'recommendation_id', $recommendation_list);
        echo $form->error($model, 'recommendation_id');
        ?>
    </div>


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

    <div class="">
        <?php
        echo $form->labelEx($model, 'election_result_id');
        echo $form->dropDownList($model, 'election_result_id', $recommendation_list);
        echo $form->error($model, 'election_result_id');
        ?>
    </div>

    <hr/>

    <div class="">
        <?php
        echo $form->labelEx($model, 'url');
        echo $form->textField($model, 'url', array('size' => 50, 'maxlength' => 1000, 'placeholder' => 'URL to share'));
        echo $form->error($model, 'url');
        ?>
        <br/>
        <?php
        echo Yii::app()->params['site_url'];
        echo '/ballot/' . date('Y') . '/';
        ?>

        <br/> <br/> 
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'personal_url');
        echo $form->textField($model, 'personal_url', array('size' => 50, 'maxlength' => 2048, 'placeholder' => 'External candidate or measure url'));
        echo $form->error($model, 'personal_url');
        ?>
    </div>

    <hr/>


    <div class="">
        <?php
        echo $form->labelEx($model, 'score');
        echo $form->textField($model, 'score', array('size' => 50, 'maxlength' => 2048));
        echo $form->error($model, 'score');
        ?>
    </div>
    <hr/>


    <div class="">
        <?php echo $form->labelEx($model, 'date_published'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'BallotItem[date_published]',
            'value' => $model->date_published,
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd ' . date('h:i:s'),
                'buttonImageOnly' => 'true',
                'buttonImage' => '/themes/hslf/img/calendar.png',
                'showOn' => 'button',
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;float:left;'
            ),
        ));
        ?>
        <?php echo $form->error($model, 'date_published'); ?>
    </div>

    <div style="clear:both;"></div>


    <div class="">
        <?php echo $form->labelEx($model, 'published'); ?>
        <?php echo $form->dropDownList($model, 'published', array('yes' => 'Yes', 'no' => 'No')); ?>
        <?php echo $form->error($model, 'published'); ?>
    </div>


    <?php
    if (!$model->isNewRecord) {

        $url = CHtml::normalizeUrl(array(
                    'ballotItem/update',
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
                         target.addClass("btn-success");
                        target.html( "ballot item saved" );
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
         
                   target.html( "Could not save ballot item:<br/>" + object.responseText );
             
                //target.fadeOut(5000, function(){
                // target.removeClass("btn-danger");
              //  });
            }',
        ));
    }else
        echo CHtml::submitButton('Create');
    ?> 

    <div class="hidden update_box" id="targetdiv">a</div>

    <?php $this->endWidget(); ?>

    <br/>

    <h1>Scorecard:</h1>

    <?php
    if ($model->id):
        $new_scorecard_item = CHtml::normalizeUrl(array('scorecard/add', 'ballot_item_id' => $model->id));

        echo CHtml::link('Add a new scorecard item', $new_scorecard_item, array('target' => '_blank'));
        ?>
        <br/>
        <br/>

        <?php
        if ($model->Scorecard):

            foreach ($model->Scorecard as $card) {
                ?>

                <div class="ballot_news_item">
                    <span class="btn floatright">
                        <?php
                        $edit_scorecard_item_url = CHtml::normalizeUrl(array('scorecard/update', 'id' => $card->id));
                        echo CHtml::link('Edit', $edit_scorecard_item_url, array('target' => '_blank'));
                        ?>

                    </span>

                    <b> <?php echo $card->name; ?>:</b>
                    <br/>
                    <?php echo $card->vote; ?>
                </div>
                <?php
            }

        else:
            echo 'No scorecard';
        endif;

    else:
        echo 'You must save an item before you can add  a scorecard.';
    endif; // end test $model->BallotItemNews
    ?>

    <br/>
    <br/>

    <h1>News updates:</h1>

    <?php
    if ($model->id):
        $new_ballot_item_news_url = CHtml::normalizeUrl(array('ballotItemNews/add', 'ballot_item_id' => $model->id));

        echo CHtml::link('Add a news update', $new_ballot_item_news_url, array('target' => '_blank'));
        ?>
        <br/>
        <br/>

        <?php
        if ($model->BallotItemNews):

            foreach ($model->BallotItemNews as $ballotItemNew) {
                ?>

                <div class="ballot_news_item">
                    <span class="btn floatright">


                        <?php
                        $edit_ballot_item_news_url = CHtml::normalizeUrl(array('ballotItemNews/update', 'id' => $ballotItemNew->id));
                        echo CHtml::link('Edit', $edit_ballot_item_news_url, array('target' => '_blank'));
                        ?>

                    </span>

                    <b> <?php echo $ballotItemNew->title; ?>:</b>
                    <br/>
                    <p><?php echo $ballotItemNew->getExcerpt() ?></p>

                    <?php ?> 
                </div>
                <?php
            }

        else:
            echo 'No news updates';
        endif;

    else:
        echo 'You must save an item before you can add a news.';
    endif; // end test $model->BallotItemNews
    ?>


</div><!-- form -->