<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/static/pushcomposer/pushcomposer.css');
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
                    echo "<span class='tagPill'>{$tag ->display_name} ({$tag->name})</span>";
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

</div>