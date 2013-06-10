jQuery(document).ready(composer);


function composer($){
    
    var
    form = $("#push_composer"),
    payloadType = $("#Payload_type"),
    postRelatedInputs = $("#post_related_inputs"),
    shareRelatedInputs = $("#share_related_inputs"),
    pushMessageTextarea  = $("#PushMessage_alert"),
    payloadTitleInput = $("#Payload_title"),
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
        
        payloadTitleInput.val(message);
       
              
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

            $("#tag_list").append(clonedDropDown);
     
        });
 
   
    };
       
       
    // event binding    
    payloadType.change(updatePayloadType); 
    payloadType.trigger('change');
    pushMessageTextarea.keyup(pushMessageTextareaChange);
    
    // initialization    
    shareRelatedInputs.hide();
    postRelatedInputs.hide();
    pushMessageTextareaChange();
    initializeRecipientStep();
    
    

     
} // jquery ready/end
