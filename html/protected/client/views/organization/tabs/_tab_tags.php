<div class="row-fluid">
    <?php
    $this->widget('backend.extensions.TagSelector.TagSelector', array(
        'model' => $model,
        'model_tags' => $model->tags,
        'tag_types' => array('organization')
    ));
    ?>
</div>