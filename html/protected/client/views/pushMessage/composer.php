<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
$tenant = Yii::app()->user->getLoggedInUserTenant();
$controller_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;
$tagList = CHtml::listData($tags, 'id', 'display_name');
$jsonTagList = CJSON::encode($tagList);
$ns = "var pushcomposer_ns  = {controller_url: '" . $controller_url . "/pushMessage', tagList:$jsonTagList};";

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

    <h4 class="floatLeft leader">Message</h4>

    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Help',
        'type' => 'button',
        'htmlOptions' => array(
            'data-toggle' => 'modal',
            'data-target' => '#modalHelp',
            'id' => 'helpButton'
        ),
    ));
    ?>
    <div class="clearfix"></div>
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
            echo $this->renderPartial('composer/_recipient', array('form' => $form, 'pushMessage' => $pushMessage, 'payload' => $payload));
            ?>
        </div>

    </div>

    <div class="row">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Message', 'htmlOptions' => array('id' => 'sendNotificationBtn', 'style' => 'float:left;')));
        ?>
    </div>
</div>
<?php
$this->endWidget();

$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'modalHelp',
    'autoOpen' => false,
    'htmlOptions' => array(
        'style' => 'width:50%;left:40%'
    ),
));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Push Composer</h4>
</div>
<?php
echo $this->renderPartial('composer/_help');
?>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    $this->endWidget();
    ?>
</div>