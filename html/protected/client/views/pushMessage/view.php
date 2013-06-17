<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
$cs->registerScriptFile($baseUrl . '/static/pushcomposer/report.js');
$tenant = Yii::app()->user->getLoggedInUserTenant();
$controller_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;
$pushId = $pushMessage->push_identifier;
$ns = "var pushmessage_ns  = {controller_url: '" . $controller_url . "/pushMessage', pushId:'$pushId'};";
Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);







$status = $pushMessage->isDelivered() ? "Delivered" : "Not delivered";


$this->secondaryNav['name'] = 'Push Messages';
$this->secondaryNav['url'] = array('pushMessage/index');
$this->secondaryNav['items'] = array('', array('label' => 'Composer', 'url' => array('composer')), '');
?>

<div class="form">
    <h4 class="leader">Message</h4>
    <div class="step row" >
        <div class="span12">
            <p><?php echo $pushMessage->alert; ?></p>
        </div>
    </div>

    <h4 class="leader">Recipient Type</h4>
    <div class="step row" >
        <div class="span12">
            <p><?php echo $pushMessage->recipient_type; ?></p>
        </div>
    </div>

    <h4 class="leader">Status</h4>
    <div class="step row" >
        <div class="span12">
            <p><?php echo $status; ?></p>
            <em>Push ID: <?php echo $pushMessage->push_identifier; ?></em>
        </div>
    </div>

    <h4 class="leader">Tags</h4>
    <div class="step row" >
        <div class="span12">
            <?php
            if ($pushMessage->tags) {
                foreach ($pushMessage->tags as $tag) {
                    echo "<span class='tagPill'>{$tag->display_name} ({$tag->name})</span>";
                }
            } else {
                echo '<p>No tags set</p>';
            }
            ?>
        </div>
    </div>

    <h4 class="leader">Creation Date</h4>
    <div class="step row" >
        <div class="span12">
            <p><?php echo $pushMessage->creation_date; ?></p>
        </div>
    </div>

    <h4 class="leader">Statistics</h4>
    <div class="step row" >
        <div class="span12">
            <div id="pushStats">Loading ...</div>
        </div>
    </div>


</div>