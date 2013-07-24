<div id="organizationContacts" class="row-fluid">
    <?php
    echo $form->labelEx($model, 'contacts');
    ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Placeholder"></a>

    <?php
    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
        ),
    ));
    ?>
</div>