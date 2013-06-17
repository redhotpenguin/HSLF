<?php echo $form->errorSummary($payload); ?>

<div class="row-fluid">
    <?php echo $form->labelEx($payload, 'title'); ?>
    <?php echo $form->textField($payload, 'title', array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11')); ?>
    <?php echo $form->error($payload, 'title'); ?>
</div>

<div class="">
    <?php
    echo $form->labelEx($payload, 'type');
    echo $form->dropDownList($payload, 'type', $payload->getTypeOptions());
    echo $form->error($payload, 'type');
    ?>
</div>

<div id="post_related_inputs">
    <hr/>
    <div class="row-fluid">
        <?php echo $form->labelEx($payload, 'post_number'); ?>
        <?php echo $form->textField($payload, 'post_number', array('placeholder' => '', 'size' => 60, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Ex: wordpress post id"></a>
        <?php echo $form->error($payload, 'post_number'); ?>
    </div>
</div>

<div id="share_related_inputs">
    <hr/>
    <div class="row-fluid">
        <?php echo $form->labelEx($payload, 'url'); ?>
        <?php echo $form->textField($payload, 'url', array('placeholder' => '', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Target URL (for Facebook and Email shares)"></a>
        <?php echo $form->error($payload, 'url'); ?>
    </div>



    <div class="row-fluid">
        <?php echo $form->labelEx($payload, 'description'); ?>
        <?php echo $form->textArea($payload, 'description', array('placeholder' => '', 'rows' => 6, 'cols' => 50, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Link description for Facebook excerpts"></a>
        <?php echo $form->error($payload, 'description'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($payload, 'tweet'); ?>
        <?php echo $form->textArea($payload, 'tweet', array('placeholder' => '', 'rows' => 2, 'cols' => 50, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Exact content of the Tweet"></a>
        <?php echo $form->error($payload, 'tweet'); ?>
    </div>
</div>
