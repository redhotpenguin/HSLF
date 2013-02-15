<div class="row-fluid">
    <?php
    $this->widget('backend.extensions.TagSelector.TagSelector', array(
        'model' => $model,
        'tag_types' => array('alert')
    ));
    ?>
</div>