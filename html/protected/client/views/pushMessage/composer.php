<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
$tagList = CHtml::listData($tags, 'id', 'display_name');
$jsonTagList = CJSON::encode($tagList);
$ns = "var pushcomposer_ns  = {tagList:$jsonTagList};";

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);

$this->secondaryNav['name'] = 'Push Messages';
$this->secondaryNav['url'] = array('pushMessage/index');
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'push_composer',
        ));
?>
<div class="form">

    <h4 class="leader">Message</h4>
    <div class="step row" >

        <div class="span12">
            <?php
            echo $this->renderPartial('composer/_message', array('form' => $form, 'pushMessage' => $pushMessage));
            ?>
        </div>

    </div>

    <h4 class="leader">Action</h4>
    <div class="step row">

        <div class="span12">
            <?php
            echo $this->renderPartial('composer/_payload', array('form' => $form, 'payload' => $payload));
            ?>
        </div>

    </div>

    <h4 class="leader">Recipients</h4>
    <div class="step row">

        <div class="span12">
            <?php
            echo $this->renderPartial('composer/_recipient', array('form' => $form, 'payload' => $payload));
            ?>
        </div>

    </div>

    <div class="row">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('id' => 'sendNotificationBtn', 'style' => 'float:left;')));
        ?>
    </div>
</div>
<?php
$this->endWidget();
?>