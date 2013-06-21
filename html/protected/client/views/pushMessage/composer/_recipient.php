<div id="recipientSection">

    <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>
    <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


    <div id="recipient-choices">
        <?php
        $recipientTypes = $pushMessage::getRecipientTypes();
        echo CHtml::radioButtonList('recipient_type', $pushMessage->recipient_type, $recipientTypes, array('separator' => ''));
        ?>
    </div>

    <div class="clearfix"></div>

    <div id="recipient-options">
        <div id="broadcastChoice"><p>All devices that have registered with this application.</p></div>
        <div id="tagListChoice">
            <p>All devices that have at least one of the following tags.</p>
            <div class="row-fluid">
                <?php
                $this->widget('backend.extensions.TagSelector.TagSelector', array(
                    'model' => $pushMessage,
                    'tag_types' => array('organization')
                ));
                ?>
            </div>
        </div>
        <div id="singleDeviceChoice">
            <p>A unique device identified by an ID.</p>
            <input class="span6" type="text" name="device_id" placeholder="Enter a Device ID"/></div>
        <div id="segmentChoice"> 
            <p>All devices that are in this segment.</p>
            <select class="span6" name="segment_id" id="segmentSelectInput">
                <option>Loading Segments...</option></select>
        </div>
    </div>

</div>