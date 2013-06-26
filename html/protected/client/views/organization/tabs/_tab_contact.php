<div class="row-fluid">
    <?php
    echo $form->labelEx($model, 'contacts');

    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
        ),
    ));
    ?>
</div>