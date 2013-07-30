
<div id="organizationDescription">
    <?php
    echo $form->labelEx($model, 'description');
    ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Add text, links and other information to the Organization Detail Page. To add a new link, place the cursor in the editor and click “Link.” To edit an existing link, highlight the link in the editor and click “Link” to update the URL."></a>
    <div class="clearfix"></div>
    <?php
    $this->widget('backend.extensions.tinymce.TinyMce', array(
        'model' => $model,
        'attribute' => 'description',
        'htmlOptions' => array(
            'rows' => 10,
            'class' => 'span12',
        ),
    ));

    echo $form->error($model, 'detail');
    ?>

</div>