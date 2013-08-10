<div class="row-fluid">
    <?php
    $this->widget('backend.extensions.TagSelector.TagSelector', array(
        'model' => $model,
        'model_tags' => $model->tags,
        'tag_types' => array('alert', 'organization'),
        'display_tag_creator' => false,
        'options' => array(
            'help_text' => 'Change the tags associated with a message in the Alert Inbox. To add a tag, type in the search bar and choose from the options that appear. Click “Remove” next to a tag to remove it.'
        )
    ));
    ?>
</div>