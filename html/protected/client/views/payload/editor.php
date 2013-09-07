<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '');


$this->headerButton = Chtml::linkButton('Delete', array(
            'class' => 'btn btn-danger',
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => 'Are you sure you want to delete this payload?'
        ));

}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Payloads';
$this->secondaryNav['url'] = array('payload/index');
?>



<div class="form">

    <?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/static/payload/payload.js');

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'payload-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('placeholder' => '', 'size' => 60, 'maxlength' => 512, 'class' => 'span11')); ?>
        <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="For Share payload, appears as preview text on share screens, Facebook share title, email subject line. For push to post, not displayed on the app."></a>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTypeOptions());
        echo $form->error($model, 'type');
        ?>
    </div>
    <hr/>
    <div id="post_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'post_number'); ?>
            <?php echo $form->textField($model, 'post_number', array('placeholder' => '', 'size' => 60, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Ex: wordpress post id"></a>
            <?php echo $form->error($model, 'post_number'); ?>
        </div>
    </div>

    <div id="share_related_inputs">
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'url'); ?>
            <?php echo $form->textField($model, 'url', array('placeholder' => '', 'size' => 60, 'maxlength' => 2048, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Target URL (for Facebook and Email shares)"></a>
            <?php echo $form->error($model, 'url'); ?>
        </div>



        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description', array('placeholder' => '', 'rows' => 6, 'cols' => 50, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Link description for Facebook excerpts"></a>
            <?php echo $form->error($model, 'description'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'tweet'); ?>
            <?php echo $form->textArea($model, 'tweet', array('placeholder' => '', 'rows' => 2, 'cols' => 50, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Exact content of the Tweet"></a>
            <?php echo $form->error($model, 'tweet'); ?>
        </div>

        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('placeholder' => '', 'size' => 60, 'maxlength' => 320, 'class' => 'span11')); ?>
            <a href="#" class="icon-question-sign" rel="tooltip" data-placement="right" title="Recipient email address (optional)"></a>

            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <br/>

    <div class="row-fluid buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php
    $this->endWidget();
    ?>

</div><!-- form -->