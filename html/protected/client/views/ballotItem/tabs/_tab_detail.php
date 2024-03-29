<div>
    <?php
    $this->widget('backend.extensions.tinymce.TinyMce', array(
        'model' => $model,
        'attribute' => 'detail',
        'htmlOptions' => array(
            'rows' => 30,
            'class' => 'span11',
        ),
    ));

    echo $form->error($model, 'detail');
    ?>
</div>

<hr/>

<div class="row-fluid">
    <div class="span6">
        <?php
        echo $form->labelEx($model, 'party_id');
        echo $form->dropDownList($model, 'party_id', CHtml::listData(Party::model()->findAll(array('order'=>'id ASC')), 'id', 'name'), array('class' => 'span11'));
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
                'class' => 'span11'
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
        echo $form->dropDownList($model, 'recommendation_id', $recommendation_list, array('class' => 'span11'));
        echo $form->error($model, 'recommendation_id');
        ?>
    </div>

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'image_url');
        echo $form->textField($model, 'image_url', array('class' => 'span11', 'placeholder' => 'http://www.mysite.com/image.jpg'));
        echo $form->error($model, 'image_url');
        ?>
    </div>

</div>
<hr/>
<div class="row-fluid">
    <div class="span6">
        <?php
        echo $form->labelEx($model, 'facebook_url');
        echo $form->textField($model, 'facebook_url', array('class' => 'span11', 'maxlength' => 2048, 'placeholder' => 'Facebook URL'));
        ?>
        <?php
        echo $form->error($model, 'facebook_url');
        ?>
    </div>

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'twitter_handle');
        echo $form->textField($model, 'twitter_handle', array('class' => 'span11', 'maxlength' => 16, 'placeholder' => 'Twitter handle'));
        ?>
        <?php
        echo $form->error($model, 'twitter_handle');
        ?>
    </div>
</div>
<hr/>
<div class="row-fluid">
    <div class="span6">
        <?php
        echo $form->labelEx($model, 'slug');
        echo $form->textField($model, 'slug', array('class' => 'span11', 'maxlength' => 1000, 'placeholder' => 'Web app. slug'));
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
        echo $form->textField($model, 'website', array('class' => 'span11', 'maxlength' => 2048, 'placeholder' => 'Candidate or measure\'s website'));
        ?>

        <?php
        echo $form->error($model, 'website');
        ?>
    </div>
</div>
