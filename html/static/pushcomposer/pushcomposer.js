jQuery(document).ready(composer);


function composer($){
    
    var 
    payloadType = $("#Payload_type"),
    post_related_inputs = $("#post_related_inputs"),
    share_related_inputs = $("#share_related_inputs"),
    pushMessageTextarea  = $("#PushMessage_alert"),
    updatePayloadType = function(){
        var type = this.value;
            
        if(type == 'share'){
            share_related_inputs.show();
            post_related_inputs.hide();
        }else if(type == 'post'){
            post_related_inputs.show();
            share_related_inputs.hide();
        }else{
            post_related_inputs.hide();
            share_related_inputs.hide();
        }
    },
    updateCharacterCounter = function(e){
        var textarea =  pushMessageTextarea.val();
        var previewChars = $("#previewChars");
        previewChars.removeClass('badge-success badge-warning badge-important');
        var charLeft = 140 - ( textarea ? textarea.length : 0 );
       
        if(charLeft > 10){
            previewChars.addClass('badge badge-success');
        }
        else if(charLeft > -1){
            previewChars.addClass('badge badge-warning');
        }
        else{
            previewChars.addClass('badge badge-important');
        }
              
        previewChars.html( charLeft  );
    },
    deleteDropDown =  function (){
        $(this).parent().remove();
    },
    initializeRecipientStep = function (data){
       
        
        var  addTagBtn = $("#add_tag_btn"),
        deleteTagSpan = $("#delete_tag_original");
     
        // build a tag dropdown
        var tagList = "";
        $.each(pushcomposer_ns.tagList, function (id, displayName) {
            tagList += "<option value='" + id + "'>" + displayName + "</option>";
        });
    
        var contactDropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='TagIds[]'>"+tagList+"</select></div>");
  
        $('.deleteBtn').click(deleteDropDown);

        $("#addTagBtn").click(function(){

            var clonedDropDown = contactDropDown.clone();
        
            var deleteButton = $('<div>').attr('class', 'span3 btn btn-warning').attr('id','').text("delete").appendTo(clonedDropDown).click(deleteDropDown);

            $("#tag_list").append(clonedDropDown);
     
        });
 
   
    };
       
        
    // event binding    
    payloadType.change(updatePayloadType); 
    payloadType.trigger('change');
    pushMessageTextarea.keyup(updateCharacterCounter);
    
    // initialization    
    share_related_inputs.hide();
    post_related_inputs.hide();
    updateCharacterCounter();
    initializeRecipientStep();
    
    

     
} // jquery ready/end
