<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'candidate-form',
        'enableAjaxValidation' => true,
        'stateful' => true,
            ));
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size'=>111,  'class' => 'span9',)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>


    <div class="row">
        <?php
        echo $form->labelEx($model, 'content');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'content',
            'htmlOptions' => array(
                'rows' => 20,
                'cols' => 85,
                'class' => 'span9',
            ),
        ));

        echo $form->error($model, 'content');
        ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'excerpt'); ?>
        <?php echo $form->textArea($model, 'excerpt', array('rows'=>5, 'cols'=>85,  'class' => 'span9',)); ?>
        <?php echo $form->error($model, 'excerpt'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'date_published'); ?>
        <?php
        
        if( empty( $model->date_published ) )
           $model->date_published = date('Y-m-d h:i:s');
        
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'BallotItemNews[date_published]',
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

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->