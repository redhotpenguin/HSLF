jQuery(document).ready(composer);


function composer($){
    var payloadType = $("#Payload_type"),
    postRelatedInputs = $("#post_related_inputs"),
    shareRelatedInputs = $("#share_related_inputs"),
    pushMessageTextarea  = $("#PushMessage_alert"),
    payloadTitleInput = $("#Payload_title"),
    sendNotificationBtn = $("#sendNotificationBtn"),
    recipientTypeInputs = $('#recipientSection input[name="recipient_type"]'),
    tagListChoice = $("#tagListChoice"),
    broadcastChoice = $("#broadcastChoice"),
    segmentChoice = $("#segmentChoice"),
    segmentSelectInput = $("#segmentSelectInput"),
    singleDeviceChoice = $("#singleDeviceChoice"),
    updatePayloadType = function(){
        var type = this.value;
            
        if(type == 'share'){
            shareRelatedInputs.show();
            postRelatedInputs.hide();
        }else if(type == 'post'){
            postRelatedInputs.show();
            shareRelatedInputs.hide();
        }else{
            postRelatedInputs.hide();
            shareRelatedInputs.hide();
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
        
        
        // uncomment when debuggign
        // payloadTitleInput.val(message);
       
              
        previewChars.html( charLeft  );
    },
    deleteDropDown =  function (){
        $(this).parent().remove();
    },
    initializeRecipientStep = function (data){
     
        // build a tag dropdown
        var tagList = "";
        $.each(pushcomposer_ns.tagList, function (id, displayName) {
            tagList += "<option value='" + id + "'>" + displayName + "</option>";
        });
    
        var contactDropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='TagIds[]'>"+tagList+"</select></div>");
  
        $('.deleteBtn').click(deleteDropDown);

        $("#addTagBtn").click(function(){

            var clonedDropDown = contactDropDown.clone();
        
            $('<div>').attr('class', 'span3 btn btn-warning').attr('id','').text("delete").appendTo(clonedDropDown).click(deleteDropDown);

            tagListChoice.append(clonedDropDown);
     
        });
 
   
    },
    setPayloadTitleModified = function(){
        payloadTitleInputModified = true;
    }
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
    payloadTitleInput.change(setPayloadTitleModified);
    sendNotificationBtn.click(formSubmitEvent);
    recipientTypeInputs.bind('change', recipientChoiceChange);

    
    // initialization    
    shareRelatedInputs.hide();
    postRelatedInputs.hide();
    pushMessageTextareaChange();
    initializeRecipientStep();
    payloadType.trigger('change');
    populateSegmentList();
    $("#recipient_type_0").change();    
} // jquery ready/end
