<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/pushcomposer.js');
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
$tenant = Yii::app()->user->getLoggedInUserTenant();
$controller_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;

$ns = array(
    'controller_url' => $controller_url . "/pushMessage",
    'action_help' => array(
        'none' => '',
        'share' => 'The text that will appear as the preview text on the share screen, the title of the shared item on Facebook and the subject line in the email share.',
        'post' => 'The title of the post being shared. This stores the action in the dashboard.'
    )
);

$ns = 'var pushcomposer_ns = ' . json_encode($ns);

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);

$this->secondaryNav['name'] = 'Push Notifications History';
$this->secondaryNav['url'] = array('pushMessage/index');

$this->header = "Push Notification Composer";

$mobileUserPageLink = Chtml::link("“Mobile Users”", array('mobileUser/index'));
$this->introText = 'Create and send targeted messages to your users\' devices and save them to the Alert Inbox in the app. iOS users must manually opt in to receive notifications. Android users are automatically opted in when downloading the app. To see how many users are opted-in or have specific tags, visit the ' . $mobileUserPageLink . ' page.';
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
            echo $this->renderPartial('composer/_recipient', array('form' => $form, 'pushMessage' => $pushMessage, 'payload' => $payload, 'tagTypes' => $tagTypes, 'device_id' => $device_id));
            ?>
        </div>

    </div>

    <div class="row">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'size' => 'large', 'label' => 'Send Push Notification', 'htmlOptions' => array('id' => 'sendNotificationBtn', 'style' => 'float:left;')));
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
        'class' => 'modalHelp'
    ),
));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Push Notification Composer</h4>
    <?php echo '<a class="modalContactLink" target="_blank" href="mailto:' . Yii::app()->params['support_email'] . '?subject=[Mobile Dashboard Support]">Contact Support</a>' ?>
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