<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'organization-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="">
        <?php
        echo $form->labelEx($model, 'name');
        echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span9', 'maxlength' => 512));
        echo $form->error($model, 'name');
        ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'display_name');
        echo $form->textField($model, 'display_name', array('size' => 60, 'class' => 'span9'));
        echo $form->error($model, 'display_name');
        ?>
    </div>


    <div class="">
        <?php
        echo $form->labelEx($model, 'slug');
        echo $form->textField($model, 'slug', array('size' => 60, 'class' => 'span9'));
        echo $form->error($model, 'slug');
        ?>
    </div>


    <div class="">
        <?php
        echo $form->labelEx($model, 'description');
        $this->widget('ext.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'description',
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
        echo $form->labelEx($model, 'website');
        echo $form->textField($model, 'website', array('size' => 60, 'maxlength' => 2048, 'class' => 'span9'));
        echo $form->error($model, 'website');
        ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'image_url');
        echo $form->textField($model, 'image_url', array('size' => 60, 'maxlength' => 2048, 'class' => 'span9'));
        echo $form->error($model, 'image_url');
        ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'facebook_url');
        echo $form->textField($model, 'facebook_url', array('size' => 60, 'maxlength' => 1024, 'class' => 'span9'));
        echo $form->error($model, 'facebook_url');
        ?>
    </div>


    <div class="">
        <?php
        echo $form->labelEx($model, 'twitter_handle');
        echo $form->textField($model, 'twitter_handle', array('size' => 60, 'maxlength' => 140, 'class' => 'span9'));
        echo $form->error($model, 'twitter_handle');
        ?>
    </div>


    <div class="">
        <?php
        echo $form->labelEx($model, 'address');
        echo $form->textArea($model, 'address', array('cols' => 60, 'rows' => 3, 'class' => 'span9'));
        echo $form->error($model, 'address');
        ?>
    </div>
    
        <div class="row-fluid">
        <?php
       /* $this->widget('ext.TagSelector.TagSelector', array(
          //  'model' => $model,
        //    'attribute' => 'district_id',
            'options' => array(
                'model_name' => 'Item',
            ),
        ));*/
        
       echo 'yo '. $model->linkTag(2);
        
       // print_r( $model->getTags() );
        
        ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->