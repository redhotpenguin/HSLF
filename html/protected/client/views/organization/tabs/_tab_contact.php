<div id="organizationContacts" class="row-fluid">
    <?php
    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
            'help_text' => 'place holder',
            'dropdown_text' => 'Select contact to assign to organization'
        ),
    ));
    ?>
</div>