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
        var query ='/client/ouroregon/pushComposer/'+action+'/?direction='+direction;
        
        var data = {
        };
        
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
    }
    
    self.handleRecipientStep = function (data){
        dynamicComposerContent.html(data.html);
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



} 

