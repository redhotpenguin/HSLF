
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