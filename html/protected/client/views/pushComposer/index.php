<?php
$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');


$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Back', 'htmlOptions' => array('style' => 'float:left;', 'id' => 'composerBackBtn')));

$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Next', 'htmlOptions' => array('style' => 'float:right;', 'id' => 'composerNextBtn')));

echo CHtml::hiddenField('virtualSessionId', $virtualSessionId);
?>

<div class="clearfix"></div>

<hr/>

<div id="dynamicComposerContent"></div>
<div id="loadingIndicator">loading...</div>
<div class="alert alert-error" id="errorIndicator"></div>
