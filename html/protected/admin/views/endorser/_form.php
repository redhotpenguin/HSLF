<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'endorser-form',
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
        echo $form->textField($model, 'website', array('size' => 60, 'maxlength' => 2048, 'class' => 'span7'));
        echo $form->error($model, 'website');
        ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'image_url');
        echo $form->textField($model, 'image_url', array('size' => 60, 'maxlength' => 2048, 'class' => 'span7'));
        echo $form->error($model, 'image_url');
        ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->