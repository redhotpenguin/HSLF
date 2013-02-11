<div class="row-fluid">
    <?php
    $this->widget('ext.TagSelector.TagSelector', array(
        'model' => $model,
        'tag_types' => array('alert')
    ));
    ?>
</div>