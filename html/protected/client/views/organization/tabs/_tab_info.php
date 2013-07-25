<div class="row-fluid">

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'name');
        echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
        echo $form->error($model, 'name');
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Listed as the Organization’s title in the list of Organizations and as the header on the Organization Detail page."></a>

    </div>

    <div  class="span6">
        <?php
        echo $form->labelEx($model, 'display_name');
        echo $form->textField($model, 'display_name', array('size' => 60, 'class' => 'span11'));
        echo $form->error($model, 'display_name');
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Listed as the Organization’s subtitle in the list of Organizations and on the Organization Detail Page."></a>

    </div>
</div>



<div class="row-fluid">
    <div class="span6">
        <?php
        echo $form->labelEx($model, 'facebook_url');
        echo $form->textField($model, 'facebook_url', array('size' => 60, 'maxlength' => 1024, 'class' => 'span11'));
        echo $form->error($model, 'facebook_url');
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Linked to from the Facebook button on the Organization Detail Page."></a>
    </div>


    <div class="span6">
        <?php
        echo $form->labelEx($model, 'twitter_handle');
        echo $form->textField($model, 'twitter_handle', array('size' => 60, 'maxlength' => 140, 'class' => 'span11'));
        echo $form->error($model, 'twitter_handle');
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Linked to from the Twitter button on the Organization Detail Page. Enter only the Twitter handle without the @ sign."></a>
    </div>
</div>

<div class="row-fluid">

    <div  class="span6">
        <?php
        echo $form->labelEx($model, 'website');
        echo $form->textField($model, 'website', array('size' => 60, 'maxlength' => 2048, 'class' => 'span11'));
        echo $form->error($model, 'website');
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Linked to from the Website button on the Organization Detail Page. Must begin with http://"></a>

    </div>

</div>

<div>
    <?php
    echo $form->labelEx($model, 'address');
    $this->widget('backend.extensions.tinymce.TinyMce', array(
        'model' => $model,
        'attribute' => 'address',
        'htmlOptions' => array(
            'rows' => 3,
            'class' => 'span12',
        ),
    ));

    echo $form->error($model, 'detail');
    ?>
</div>