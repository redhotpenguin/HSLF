<?php
$this->secondaryNav['name'] = 'Push Composer';
$this->secondaryNav['url'] = array('pushComposer/index');

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/global/js/form/jquery.multipage.js');
$cs->registerCssFile($baseUrl . '/static/global/css/jquery.multipage.css');
?>


<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'push_composer',
    'type' => 'horizontal',
        ));

echo $this->renderPartial('composer/_message', array('form' => $form, 'pushMessageModel' => $pushMessageModel));

echo $this->renderPartial('composer/_recipients', array('form' => $form));

echo $this->renderPartial('composer/_action', array('form' => $form));

$this->endWidget();
?>
