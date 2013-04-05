jQuery(document).ready(composer);


function composer($){
    
    var 
    self = this,
    composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    errorIndicator = $("#errorIndicator"),
    steps = ['Message','Payload','Recipient','Validation','Confirmation'],
    currentStepIndex = 0
    validatedData = []; // experimental, store valided models
    
    composerNextBtn.live('click',function(){
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
            console.log(jqXHR.responseText);
            dynamicComposerContent.show();
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
        
        if(validatedData['tags']){                       
            
            $.each(validatedData['tags'], function(index,tagName){
                
                var clonedTagBoxCount = $("#tag_list .tagBox").length;
                
                var newTagBox =   $("#original_tag").clone().attr("id", "tagBox"+clonedTagBoxCount);
                
                var tagInput = newTagBox.find(".tagInput");
                
                tagInput.val(tagName)
                tagInput.attr("id", "");
                newTagBox.append(deleteTagSpan.clone().css("display", "inline").click(function(){
                    $(this).parent(".tagBox").remove();
                }));
                
                
                $("#tag_list").append(newTagBox);
            
            });
            
            $("#original_tag").hide();
        }
        
        
        addTagBtn.click(function(){
            
            var clonedTagBoxCount = $("#tag_list .tagBox").length;
            
            var newTagBox =   $("#original_tag").clone().attr("id", "tagBox"+clonedTagBoxCount);
            
            var tagInput = newTagBox.find(".tagInput");
            
            tagInput.val("")
            tagInput.attr("id", "");
            newTagBox.append(deleteTagSpan.clone().css("display", "inline").click(function(){
                $(this).parent(".tagBox").remove();
            }));
            newTagBox.show();
            
            $("#tag_list").append(newTagBox);
        });
    }
    
    self.handleValidationStep = function(data){
        
        
        dynamicComposerContent.html(data.html);
                
        $('#pushMessageArea').val( validatedData['pushMessage'].alert ).attr('readonly','readonly');
        
        var tagList = $("#tagList");
        $.each(validatedData['tags'], function(index,tagName){
            tagList.append("<span  class='tagPill'>"+tagName+"</span>");
        });
        
    };
    
    self.handleConfirmationStep = function(data){
        
        dynamicComposerContent.html(data.html);
        
        
        
        
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

