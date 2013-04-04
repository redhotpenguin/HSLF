<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'push_composer',
        ));
?>

<div class="clearfix"></div>

<fieldset id="review">
    <h1>Confirmation</h1>
</fieldset>


<h3>Alert</h3>
<div id="pushMessage">
    <textarea id="pushMessageArea" name="Validation[push_message] "></textarea>
</div>


<h3>Payload</h3>
<div id="payload"></div>


<h3>Tags</h3>
<div id="tagList"></div>

<div class="clearfix"></div>

<hr/>

<?php
$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('id' => 'composerNextBtn')));
$this->endWidget();
?>