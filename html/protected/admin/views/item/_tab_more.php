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