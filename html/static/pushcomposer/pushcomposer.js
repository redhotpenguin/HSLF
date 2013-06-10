jQuery(document).ready(composer);


function composer($){
    
    var 
    self = this,
    composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    errorIndicator = $("#errorIndicator"),
    messageTextarea = $("#PushMessage_alert"),
    progressBar = $('#progressBar'),
    steps = [ 'Message','Payload','Recipient','Validation'],
    totalStepNumber = steps.length,
    currentStepIndex = 0
    validatedData = []; // store valided models
    
    composerNextBtn.live('click',function(){
        if(currentStepIndex == 3){
            if(confirm("Are you sure you want to send this alert?") == false)
                return;
        }
        updateFormState(steps[currentStepIndex], 'next');
    });
    
    composerBackBtn.live('click',function(){  
        updateFormState(steps[currentStepIndex], 'back');
    });
    
    
    function updateFormState(action,direction){         
        var query = pushcomposer_ns.controller_url + '/'+action+'/?direction='+direction;
        
        var data = {};
        
        data = $("#push_composer").serialize();
               
        dynamicComposerContent.fadeOut(100);
        $.ajax({
            url:query,
            type:'POST',
            data:data
        }).success(function(result){
            
            var fnct = 'handle'+steps[currentStepIndex]+'Step';
            
            self[fnct](result);
            
            if(result.proceedToNextStep){
                currentStepIndex+=1;   
                updateFormState(steps[currentStepIndex], 'next');
            }
            else if(result.proceedToLastStep){
                currentStepIndex -=1;   
                updateFormState(steps[currentStepIndex], 'next');
            }else if(result.end){
                currentStepIndex = totalStepNumber;
            }
            
            updateProgressBar(currentStepIndex);

            dynamicComposerContent.fadeIn(100);
        
        }).fail(function(jqXHR, textStatus){
            errorIndicator.html(jqXHR.responseText);
            dynamicComposerContent.show();
            errorIndicator.show();
        });
    
    }
    
    
    self.handleMessageStep = function(data){
        if(data.validatedModel != undefined && data.validatedModel.pushMessage != undefined){
            validatedData['pushMessage'] = data.validatedModel.pushMessage;
        }
        
        dynamicComposerContent.html(data.html);
        
        if(validatedData['pushMessage']){
            populateFormFromModel(validatedData['pushMessage']);
        }
        updateCharacterCounter();
    }
    
    self.handlePayloadStep = function(data){
      
            
        if(data.validatedModel != undefined && data.validatedModel.payload != undefined){
            validatedData['payload'] = data.validatedModel.payload;
        }
        
        dynamicComposerContent.html(data.html);
        
        
        var payload_type = $("#Payload_type"),
        post_related_inputs = $("#post_related_inputs"),
        share_related_inputs = $("#share_related_inputs"),
        payloadTitle = $("#Payload_title");
        
        if(validatedData['payload']){
            populateFormFromModel(validatedData['payload']);
        }else{ // first time form is displayed
            payloadTitle.val(validatedData['pushMessage'].alert);
        }
        

        
        share_related_inputs.hide();
        post_related_inputs.hide();
        
        payload_type.change(function(){
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
        });
        
        payload_type.trigger('change');
    }
    
    self.handleRecipientStep = function (data){
        
        if(data.validatedModel != undefined && data.validatedModel.tagIds != undefined){
            validatedData['tagIds'] = data.validatedModel.tagIds;
        }
        
        dynamicComposerContent.html(data.html);
        
        var  addTagBtn = $("#add_tag_btn"),
        deleteTagSpan = $("#delete_tag_original");
     
        // build a tag dropdown
        var tagList = "";
        $.each(pushcomposer_ns.tagList, function (id, displayName) {
            tagList += "<option value='" + id + "'>" + displayName + "</option>";
        });
    
        var contactDropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='TagIds[]'>"+tagList+"</select></div>");

        if(validatedData['tagIds']){                       
            
            $.each(validatedData['tagIds'], function(index,tagId){
                                
                var dropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='TagIds[]'>"+tagList+"</select></div>");
                
                dropDown.find(':input').val(tagId);
                                        
                $('<div>').attr('class', 'span3 btn btn-warning').attr('id','').text("delete").appendTo(dropDown).click(deleteDropDown);

                $("#tag_list").append(dropDown);
            
            });
            
            $("#original_tag").hide();
        }
        
        $('.deleteBtn').click(deleteDropDown);

        $("#addTagBtn").click(function(){

            var clonedDropDown = contactDropDown.clone();
        
            var deleteButton = $('<div>').attr('class', 'span3 btn btn-warning').attr('id','').text("delete").appendTo(clonedDropDown).click(deleteDropDown);

            $("#tag_list").append(clonedDropDown);
     
        });
 
        function deleteDropDown(){
            $(this).parent().remove();
        }
    }
    
    self.handleValidationStep = function(data){
        
        dynamicComposerContent.html(data.html);
      
      
      // test data. uncomment to prefill validation form
      /*
        validatedData['tagIds']  = [20, 21, 22]
       
        validatedData['pushMessage']  = {}
       
        validatedData['pushMessage'].alert = 'alert goes here';
        
        validatedData['payload'] = {
            description: "deads",
            email: "dad@gmail.com",
            id: null,
            post_number: 14,
            tenant_id: null,
            title: "ffff",
            tweet: "tweet",
            type: "share",
            url: "http://www.google.fr"
        }*/

        var hiddenInputs = $("#hiddenInputs");
        $.each(validatedData['tagIds'], function(k,v){
            hiddenInputs.append(' <input type="hidden" name="Validation[TagIds][]" value ="'+v+'" />');
        });
        
        $.each(validatedData['payload'], function(k,v){
            hiddenInputs.append(' <input type="hidden" name="Validation[Payload]['+k+']" value ="'+v+'" />');
        });
   
        var payloadTable = $("#payloadTable");
        payloadTable.append('<tr><td><strong>Title</strong></td><td>'+validatedData['payload'].title+'</td></tr>');
        payloadTable.append('<tr><td><strong>Type</strong></td><td>'+validatedData['payload'].type+'</td></tr>');

        if(validatedData['payload'].type == 'share'){
            payloadTable.append('<tr><td><strong>URL</strong></td><td>'+validatedData['payload'].url+'</td></tr>');
            payloadTable.append('<tr><td><strong>Description</strong></td><td>'+validatedData['payload'].description+'</td></tr>');
            payloadTable.append('<tr><td><strong>Tweet</strong></td><td>'+validatedData['payload'].tweet+'</td></tr>');
            payloadTable.append('<tr><td><strong>Email</strong></td><td>'+validatedData['payload'].email+'</td></tr>');
        }
        
        else if(validatedData['payload'].type == 'post'){
            payloadTable.append('<tr><td><strong>Post number</td><td>'+validatedData['payload'].post_number+'</td></tr>');
        }
     
                
        $('#pushMessageArea').val( validatedData['pushMessage'].alert ).attr('readonly','readonly');
        
        var tagList = $("#tagList");
        $.each(validatedData['tagIds'], function(index,tagId){
            var tagName = getTagName(tagId);
            tagList.append("<span  class='tagPill'>"+tagName+"</span>");
        });
        
    };
    
    function getTagName(tagId){
        return pushcomposer_ns.tagList[tagId];
    }

    // heplers
    function populateFormFromModel(model){
        $.each($("#dynamicComposerContent").find(':input'), function(k,input){
            var inputName = $(input).attr('name'); // Ex: PushMessage[alert] 
            
            var property=inputName.substring(inputName.lastIndexOf("[")+1,inputName.lastIndexOf("]")); // extrat property name. Ex: alert
            if(model[property]){
                $(input).attr('value',  model[property] )
            }
        
        });
    }
    
    errorIndicator.hide();
    updateFormState(steps[currentStepIndex], 'next');


    dynamicComposerContent.on('keyup', messageTextarea, updateCharacterCounter);

    function updateCharacterCounter(e){
        var textarea =  $("#PushMessage_alert").val();
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
    }

    function updateProgressBar(stepNumber){
        var barWidth =  ( (stepNumber/totalStepNumber) * 100 ) + "%";
        
        if(stepNumber === totalStepNumber){ // last step
            $("#progressIndicator").removeClass('progress-info').addClass('progress-success');
        }
       
        progressBar.width(barWidth);
    }
     
} // jquery ready/end
