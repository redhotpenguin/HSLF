<fieldset id="action">
    <h1>Action</h1>
</fieldset>

<div class="form">

    <div class="row-fluid">
        <?php
        echo CHtml::label('Title', 'title');
        echo CHtml::textField('title', null, array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="For Share payload, appears as preview text on share screens, Facebook share title, email subject line. For push to post, not displayed on the app."></a>
    </div>

    <div class="row-fluid">
        <?php
        echo CHtml::label('Type', 'type');
        echo CHtml::dropDownList('type', null, $payloadModel->getTypeOptions());
        ?>
    </div>

    <div class="row-fluid">
        <?php
        echo CHtml::label('Post Number', 'post_number');
        echo CHtml::textField('post_number', null, array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Ex: wordpress post id"></a>
    </div>


    <div class="row-fluid">
        <?php
        echo CHtml::label('URL', 'url');
        echo CHtml::textField('url', null, array('placeholder' => '', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Target URL (for Facebook and Email shares)"></a>
    </div>

    <div class="row-fluid">
        <?php
        echo CHtml::label('Description', 'description');
        echo CHtml::textArea('url', null, array('placeholder' => '', 'rows' => 6, 'cols' => 50, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Link description for Facebook excerpts"></a>
    </div>


    <div class="row-fluid">
        <?php
        echo CHtml::label('Tweet', 'tweet');
        echo CHtml::textArea('url', null, array('placeholder' => '', 'rows' => 2, 'cols' => 50, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Exact content of the Tweet"></a>
    </div>

    <div class="row-fluid">
        <?php
        echo CHtml::label('Email', 'email');
        echo CHtml::textField('email', null, array('placeholder' => '', 'size' => 60, 'maxlength' => 320, 'class' => 'span11'));
        ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Recipient email address (optional)"></a>
    </div>

</div><!-- form -->