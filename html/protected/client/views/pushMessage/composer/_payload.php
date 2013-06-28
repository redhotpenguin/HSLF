<?php echo $form->errorSummary($payload); ?>



<div class="row-fluid">
    <?php
    echo $form->labelEx($payload, 'type');
    echo $form->dropDownList($payload, 'type', $payload->getTypeOptions());
    ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title='Determines what happens when the user opens a push notification. Select "None" for notifications that do not include an action. Select "Push to Post" to direct the user to a post from a notification. Select "Push to Share" to direct the user to a share screen from a notification.'></a>

    <?php
    echo $form->error($payload, 'type');
    ?>
</div>

<div class="row-fluid" id="payloadTitleSection">
    <?php echo $form->labelEx($payload, 'title'); ?>
    <p id="payloadTitleExplanation"></p>

    <?php echo $form->textField($payload, 'title', array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11')); ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="For Push to Post, enter the exact title of the post being shared. For Push to Share, enter the text that will be used for: the preview text on the share screen, the title of the shared item on Facebook and the subject line in the email share (all three use the same text)."></a>
    <?php echo $form->error($payload, 'title'); ?>
</div>

<div id="post_related_inputs" class="row-fluid">
    <?php
    echo $form->labelEx($payload, 'post_number');
    echo $form->textField($payload, 'post_number', array('placeholder' => '', 'size' => 60, 'class' => 'span11', 'placeholder' => 'Example: 1234'));
    ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Determines which post the user is directed to from a Push to Post. Enter the unique numerical ID of the post, which can be found in your Content Management System."></a>
    <?php echo $form->error($payload, 'post_number'); ?>
</div>

<div id="share_related_inputs" class="row-fluid">
    <?php
    echo $form->labelEx($payload, 'url');
    echo $form->textField($payload, 'url', array('placeholder' => 'Example: http://www.website.com', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11'));
    ?>
    <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Determines which link is shared via Facebook and email shares. Enter the full web address, starting with http://."></a>
    <?php echo $form->error($payload, 'url'); ?>

    <div class="row-fluid">
        <?php
        echo $form->labelEx($payload, 'description');
        echo $form->textArea($payload, 'description', array('placeholder' => '', 'rows' => 6, 'cols' => 50, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Determines the text that accompanies the link shared on Facebook (this is separate from the status update, which the user sets when sharing)."></a>
        <?php echo $form->error($payload, 'description'); ?>
    </div>

    <div class="row-fluid">
        <?php
        echo $form->labelEx($payload, 'tweet');
        echo $form->textArea($payload, 'tweet', array('placeholder' => '', 'rows' => 2, 'cols' => 50, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Determines the exact content of the tweet shared on Twitter."></a>
        <?php echo $form->error($payload, 'tweet'); ?>
    </div>
</div>
