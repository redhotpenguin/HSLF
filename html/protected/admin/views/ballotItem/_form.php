<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'ballot-item-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
    $recommendation_list = CHtml::listData(Recommendation::model()->findAll(), 'id', 'value');
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>



    <div class="row">
        <?php echo $form->labelEx($model, 'item'); ?>
        <?php echo $form->textField($model, 'item', array('size' => 111, 'maxlength' => 1000, 'placeholder' => 'i.e Candidate or Measure Name')); ?>
        <?php echo $form->error($model, 'item'); ?>
    </div>

    <div class="left_col">

        <div class="row">
            <?php
            echo $form->labelEx($model, 'item_type');
            echo $form->dropDownList($model, 'item_type', $model->getItemTypeOptions());
            echo $form->error($model, 'item_type');
            ?>
        </div>

        <div class="row">
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
        <div class="row">
            <?php
            echo $form->labelEx($model, 'party');
            echo $form->dropDownList($model, 'party', $model->getParties());
            echo $form->error($model, 'party');
            ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'image_url'); ?>
            <?php
            //echo $form->textField($model, 'image_url', array('size' => 50, 'readonly'=>'readonly')); 

            if ($model->image_url) {
                echo '<a href="' . $model->image_url . '" target="_blank"><img class="ballot_item_image_url" src="' . $model->image_url . '"/></a>';
            }
            ?>
            <?php echo $form->error($model, 'image_url'); ?>



            <input type="file" name="image_url" />

            <p>Recommended width: 320px</p>

        </div>

        <div class="row">
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
        <div class="row">
            <?php
            echo $form->labelEx($model, 'office_type');
            echo $form->dropDownList($model, 'office_type', $model->getOfficeTypes());
            echo $form->error($model, 'office_type');
            ?>
        </div>


    </div>

    <hr/>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'priority');
        echo $form->dropDownList($model, 'priority', $model->getPriorityOptions());
        echo $form->error($model, 'priority');
        ?>
        <p> 1 = Lowest, 10 = highest</p>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'recommendation_id');
        echo $form->dropDownList($model, 'recommendation_id', $recommendation_list);
        echo $form->error($model, 'recommendation_id');
        ?>
    </div>


    <div class="row">
        <?php
        echo $form->labelEx($model, 'detail');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'detail',
            'htmlOptions' => array(
                'rows' => 20,
                'cols' => 85,
            ),
        ));

        echo $form->error($model, 'detail');
        ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'election_result_id');
        echo $form->dropDownList($model, 'election_result_id', $recommendation_list);
        echo $form->error($model, 'election_result_id');
        ?>
    </div>

    <hr/>

    <div class="row">
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

    <div class="row">
        <?php
        echo $form->labelEx($model, 'personal_url');
        echo $form->textField($model, 'personal_url', array('size' => 50, 'maxlength' => 2048, 'placeholder' => 'External candidate or measure url'));
        echo $form->error($model, 'personal_url');
        ?>

    </div>


    <div class="row">
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


    <div class="row">
        <?php echo $form->labelEx($model, 'published'); ?>
        <?php echo $form->dropDownList($model, 'published', array('yes' => 'Yes', 'no' => 'No')); ?>
        <?php echo $form->error($model, 'published'); ?>
    </div>



    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

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
                    <span class="pill_btn">


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