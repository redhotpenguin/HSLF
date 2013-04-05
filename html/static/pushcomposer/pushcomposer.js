jQuery(document).ready(composer);


function composer($){
    
    var 
    self = this,
    composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    errorIndicator = $("#errorIndicator"),
    steps = ['Message','Payload','Recipient','Validation'],
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
            }
            
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
    
    }
    
    self.handlePayloadStep = function(data){
        if(data.validatedModel != undefined && data.validatedModel.payload != undefined){
            validatedData['payload'] = data.validatedModel.payload;
        }
        
        dynamicComposerContent.html(data.html);
        
        if(validatedData['payload']){
            populateFormFromModel(validatedData['payload']);
        }
        
        var payload_type = $("#Payload_type");
        post_related_inputs = $("#post_related_inputs");
        share_related_inputs = $("#share_related_inputs");
        
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
        
        if(data.validatedModel != undefined && data.validatedModel.tags != undefined){
            validatedData['tags'] = data.validatedModel.tags;
        }
        
        dynamicComposerContent.html(data.html);
        
        var  addTagBtn = $("#add_tag_btn"),
        deleteTagSpan = $("#delete_tag_original");
     
        // build a tag dropdown
        var tagList = "";
        $.each(pushcomposer_ns.tagList, function (id, displayName) {
            tagList += "<option value='" + id + "'>" + displayName + "</option>";
        });
    
        var contactDropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='Tags[]'>"+tagList+"</select></div>");

        if(validatedData['tags']){                       
            
            $.each(validatedData['tags'], function(index,tagId){
                
                var dropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='Tags[]'>"+tagList+"</select></div>");
                                        
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
            console.log($(this).parent().remove());
        }
    }
    
    self.handleValidationStep = function(data){
        
        
        dynamicComposerContent.html(data.html);
  
  /*
        validatedData['pushMessage']  = {}
        validatedData['tags']  = ['tag1', 'tag2', 'tag3']
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
        };

*/
        var hiddenInputs = $("#hiddenInputs");
        $.each(validatedData['tags'], function(k,v){
            hiddenInputs.append(' <input type="hidden" name="Validation[Tags][]" value ="'+v+'" />');
        })
        $.each(validatedData['payload'], function(k,v){
            hiddenInputs.append(' <input type="hidden" name="Validation[Payload]['+k+']" value ="'+v+'" />');
        })
   
        var payloadTable = $("#payloadTable");
        payloadTable.append('<tr><td><strong>Title</strong></td><td>'+validatedData['payload'].title+'</td></tr>');

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
        $.each(validatedData['tags'], function(index,tagName){
            tagList.append("<span  class='tagPill'>"+tagName+"</span>");
        });
        
    };

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





} 

