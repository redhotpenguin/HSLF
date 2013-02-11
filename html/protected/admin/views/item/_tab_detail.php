<div>
    <?php
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
        echo $form->textField($model, 'image_url', array('class' => 'span12', 'placeholder' => 'http://www.mysite.com/image.jpg'));
        echo $form->error($model, 'image_url');
        ?>
    </div>

</div>
