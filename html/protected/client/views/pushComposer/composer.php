<?php

$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
?>


<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'push_composer',
    'type' => 'horizontal',
        ));

?>

<div id="dynamicComposerContent">
    
</div>

<?php

$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'label' => 'Next', 'htmlOptions' => array('id' => 'composerNextBtn')));

$this->endWidget();
?>
