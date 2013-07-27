<div id="organizationTags" class="row-fluid">
    <?php
    $this->widget('backend.extensions.TagSelector.TagSelector', array(
        'model' => $model,
        'model_tags' => $model->tags,
        'tag_types' => array('organization'),
        'options' => array(
            'help_text' => 'To assign an existing tag to an Organization, begin typing the tag display name in the search bar and then select from the options that appear. To create a new tag, click “Create New Tag.” Click “Remove” next to any tag to remove it from an Organization.'
        )
    ));
    ?>
</div>