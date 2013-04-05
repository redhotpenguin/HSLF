<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'push_composer',
        ));
?>

<div class="clearfix"></div>


<fieldset id="review">
    <h1>Validation</h1>
</fieldset>

<div class="row-fluid">
    <h3>Alert</h3>

    <div id="pushMessage">
        <textarea id="pushMessageArea" class="span12" name="Validation[push_message] "></textarea>
    </div>
</div>


<div class="row-fluid">
    <h3>Payload</h3>
    <table class="table table-hover" id="payloadTable"></table>
</div>

<div class="row-fluid">
    <h3>Tags</h3>
    <div id="tagList"></div>
</div>

<div class="clearfix"></div>

<hr/>

<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('id' => 'composerNextBtn')));
$this->endWidget();
?>