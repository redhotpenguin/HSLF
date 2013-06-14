<div id="recipientSection">

    <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>
    <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


    <div id="recipient-choices">
        <?php
        $recipientTypes = $pushMessage::getRecipientTypes();
        echo CHtml::radioButtonList('recipient_type', null, $recipientTypes, array('separator' => ''));
        ?>
    </div>

    <div class="clearfix"></div>

    <div id="recipient-options">
        <div id="tagListChoice">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Add a tag',
                'htmlOptions' => array(
                    'id' => 'addTagBtn'
                ),
            ));
            ?>
        </div>
        <div id="broadcastChoice"><p>All devices that have registered with this application.</p></div>
        <div id="singleDeviceChoice"><input class="span6" type="text" name="device_id" placeholder="Enter a Device ID"/></div>
        <div id="segmentChoice"> <select class="span6" name="segment_id" id="segmentSelectInput"><option>Loading Segments...</option></select> </div>
    </div>

</div>