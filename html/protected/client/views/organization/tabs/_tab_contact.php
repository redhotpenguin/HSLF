<div id="organizationContacts" class="row-fluid">
    <?php
    $this->widget('backend.extensions.ContactSelector.ContactSelector', array(
        'model' => $model,
        'attribute' => '',
        'options' => array(
            'model_name' => 'Organization',
            'help_text' => 'Assign existing contacts to an Organization from the drop down menu. Remove a contact from an Organization by clicking “Remove.” Create a new contact and assign it to an Organization by clicking “Create New Contact.”
Edit the order contacts appear in on the Organization Detail Page by clicking and dragging the name to the position you want.
Click “Save” when you are done making changes.',
            'dropdown_text' => 'Select contact to assign to organization'
        ),
    ));
    ?>
</div>