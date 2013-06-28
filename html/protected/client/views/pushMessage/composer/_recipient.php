<?php
/**
 *
 * The followings variables are available for this view:
 */
?>

<div id="recipientSection">

    <div id="recipient-choices">
        <?php
        $recipientTypes = $pushMessage::getRecipientTypes();
        echo CHtml::radioButtonList('recipient_type', $pushMessage->recipient_type, $recipientTypes, array('separator' => ''));
        ?>
    </div>

    <div class="clearfix"></div>

    <div id="recipient-options">
        <div id="broadcastChoice"><p class="helpText">All devices that have registered with this application.</p></div>
        <div id="tagListChoice">
            <p class="helpText">All devices that have at least one of the following tags.</p>
            <div class="row-fluid">
                <?php
                $this->widget('backend.extensions.TagSelector.TagSelector', array(
                    'model' => $pushMessage,
                    'tag_types' => $tagTypes,
                    'display_tag_creator' => false,
                ));
                ?>
            </div>
        </div>
        <div id="singleDeviceChoice">
            <p class="helpText">A unique device identified by an ID.</p>
            <input class="span6" type="text" name="device_id" placeholder="Enter a Device ID"/></div>
        <div id="segmentChoice"> 
            <p class="helpText">All devices that are in this segment.</p>
            <select class="span6" name="segment_id" id="segmentSelectInput">
                <option>Loading Segments...</option></select>
        </div>
    </div>

</div>