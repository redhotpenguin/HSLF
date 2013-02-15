
<div class="">
    <?php
    echo $form->labelEx($model, 'description');
    $this->widget('backend.extensions.tinymce.TinyMce', array(
        'model' => $model,
        'attribute' => 'description',
        'htmlOptions' => array(
            'rows' => 20,
            'cols' => 185,
            'class' => 'span12',
        ),
    ));

    echo $form->error($model, 'detail');
    ?>
</div>