jQuery(document).ready(composer);


function composer($){
    var payloadType = $("#Payload_type"),
    postRelatedInputs = $("#post_related_inputs"),
    shareRelatedInputs = $("#share_related_inputs"),
    pushMessageTextarea  = $("#PushMessage_alert"),
    payloadTitleExplanation = $("#payloadTitleExplanation"),
    payloadTitleSection = $("#payloadTitleSection"),
    sendNotificationBtn = $("#sendNotificationBtn"),
    recipientTypeInputs = $('#recipientSection input[name="recipient_type"]'),
    tagListChoice = $("#tagListChoice"),
    broadcastChoice = $("#broadcastChoice"),
    segmentChoice = $("#segmentChoice"),
    segmentSelectInput = $("#segmentSelectInput"),
    singleDeviceChoice = $("#singleDeviceChoice"),
    payloadTitleHelp = $("#payloadTitleHelp"),
    updatePayloadType = function(){
        var type = this.value;
        
        postRelatedInputs.hide();
        shareRelatedInputs.hide();
        payloadTitleSection.show();
    
        if(type == 'share'){
            shareRelatedInputs.show();
            payloadTitleExplanation.html('Text that will be used for: the preview text on the share screen, the title of the shared item on Facebook and the subject line in the email share');
            payloadTitleHelp.attr('data-original-title', pushcomposer_ns.action_help.share);
        }else if(type == 'post'){
            postRelatedInputs.show();
            payloadTitleExplanation.html('Title of the post being shared');
            payloadTitleHelp.attr('data-original-title', pushcomposer_ns.action_help.post);
        }
        else{
            payloadTitleSection.hide();
            payloadTitleHelp.attr('data-original-title', pushcomposer_ns.action_help.none);
        }
        
    },
    pushMessageTextareaChange = function(e){
        var message =  pushMessageTextarea.val();
        var previewChars = $("#previewChars");
        previewChars.removeClass('badge-success badge-warning badge-important');
        var charLeft = 140 - ( message ? message.length : 0 );
       
        if(charLeft > 10){
            previewChars.addClass('badge badge-success');
        }
        else if(charLeft > -1){
            previewChars.addClass('badge badge-warning');
        }
        else{
            previewChars.addClass('badge badge-important');
        }
        
        previewChars.html(charLeft);
    },
    formSubmitEvent = function(){
        return confirm("Are you sure you want to send this alert?");
    },
    recipientChoiceChange = function(){        
        var recipientType = $(this).val();
        
        tagListChoice.hide();
        broadcastChoice.hide();
        segmentChoice.hide();
        singleDeviceChoice.hide();
        
        switch(recipientType){
            case "broadcast":
                broadcastChoice.show();
                break;
            case "tag":
                tagListChoice.show();
                break;
            case "segment":
                populateSegmentList();
                segmentChoice.show();
                break;
            case "single":
                singleDeviceChoice.show();
                break;
        }
    },
    getSegments = function(_cb){
        $.get(pushcomposer_ns.controller_url+'/jsonSegments',_cb);
    },
    populateSegmentList = function(){
        getSegments(function(segments){
            segmentSelectInput.empty();
            $.each(segments, function(segmentIndex){
                var segment = segments[segmentIndex];
                segmentSelectInput.append( $('<option name="segmentId" value="'+segment.id+'"> '+ segment.display_name +'  </option>'));
            }); 
            
        });
    }
       

    // event binding    
    payloadType.change(updatePayloadType); 
    pushMessageTextarea.keyup(pushMessageTextareaChange);
    sendNotificationBtn.click(formSubmitEvent);
    recipientTypeInputs.bind('click', recipientChoiceChange);

    
    // initialization    
    shareRelatedInputs.hide();
    postRelatedInputs.hide();
    pushMessageTextareaChange();
    payloadType.trigger('change');
    populateSegmentList();
    $('input[name=recipient_type]:checked', '#recipient_type').click();
} // jquery ready/end
