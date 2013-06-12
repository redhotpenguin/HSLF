<div id="recipientSection">

    <span class="delete_tag" style="display:none;" id="delete_tag_original">X</span>
    <span class="delete_tag" style="display:none;" id="delete_district_original">X</span>


    <div id="recipient-choices">
        <label for="id_recipient_type_broadcast"><input checked="checked" type="radio" id="id_recipient_type_broadcast" value="broadcast" name="recipient_type" checked="checked">Broadcast</label>
        <label for="id_recipient_type_tag"><input type="radio" id="id_recipient_type_tag" value="tag" name="recipient_type">Devices by Tag</label>
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
    </div>
</div>