<?php
$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');

$tenant = Yii::app()->user->getLoggedInUserTenant();

$controller_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;

$tagList = CHtml::listData($tags, 'id', 'display_name');

$jsonTagList = CJSON::encode($tagList);

$dropDownName = '[tags][]';

$ns = "var pushcomposer_ns  = {controller_url: '" . $controller_url . "/pushComposer', tagList:$jsonTagList};";

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);
?>

<div class="clearfix"></div>
<div id="progressIndicator" class="progress progress-info">
    <div class="bar" id="progressBar"S></div>
</div>
<div id="dynamicComposerContent"></div>
<div class="alert alert-error" id="errorIndicator"></div>
