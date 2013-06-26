<div class="row-fluid">

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'name');
        echo $form->textField($model, 'name', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
        echo $form->error($model, 'name');
        ?>
    </div>

    <div  class="span6">
        <?php
        echo $form->labelEx($model, 'display_name');
        echo $form->textField($model, 'display_name', array('size' => 60, 'class' => 'span11'));
        echo $form->error($model, 'display_name');
        ?>
    </div>
</div>

<div class="row-fluid">

    <div  class="span6">
        <?php
        echo $form->labelEx($model, 'website');
        echo $form->textField($model, 'website', array('size' => 60, 'maxlength' => 2048, 'class' => 'span11'));
        echo $form->error($model, 'website');
        ?>
    </div>

    <div class="span6">
        <?php
        echo $form->labelEx($model, 'image_url');
        echo $form->textField($model, 'image_url', array('size' => 60, 'maxlength' => 2048, 'class' => 'span11'));
        echo $form->error($model, 'image_url');
        ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <?php
        echo $form->labelEx($model, 'facebook_url');
        echo $form->textField($model, 'facebook_url', array('size' => 60, 'maxlength' => 1024, 'class' => 'span11'));
        echo $form->error($model, 'facebook_url');
        ?>
    </div>


    <div class="span6">
        <?php
        echo $form->labelEx($model, 'twitter_handle');
        echo $form->textField($model, 'twitter_handle', array('size' => 60, 'maxlength' => 140, 'class' => 'span11'));
        echo $form->error($model, 'twitter_handle');
        ?>
    </div>
</div>

<div class="">
    <?php
    echo $form->labelEx($model, 'address');
    echo $form->textArea($model, 'address', array('cols' => 60, 'rows' => 3, 'class' => 'span12'));
    echo $form->error($model, 'address');
    ?>
</div>