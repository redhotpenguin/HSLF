jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
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
        
        //  console.log(validatedData);
        
        var query ='/client/ouroregon/pushComposer/'+action+'/?direction='+direction;
        
        var data = {
        };
        
        data = $("#push_composer").serialize();
        
        
        $.ajax({
            url:query,
            type:'POST',
            data:data
        }).success(function(result){
            
            var step = steps[currentStepIndex];
            
            // todo: refactor using switch or dynamic function name
            if(step == 'Message')
                handleMessageStep(result);
            
            else if(step == 'Payload')
                handlePayloadStep(result);
            
            else if(step == 'Recipient')
                handleRecipientStep(result);
            
            
            if(result.proceedToNextStep){
                currentStepIndex+=1;   
                updateFormState(steps[currentStepIndex], 'next');
            }
            else if(result.proceedToLastStep){
                currentStepIndex -=1;   
                updateFormState(steps[currentStepIndex], 'next');
            }
        
        }).fail(function(jqXHR, textStatus){
            console.log(jqXHR.responseText);
        });
    
    }
    

    function handleMessageStep(data){
        if(data.validatedModel != undefined && data.validatedModel.pushMessage != undefined){
            validatedData['pushMessage'] = data.validatedModel.pushMessage;
        }
        
        dynamicComposerContent.html(data.html);
                
        if(validatedData['pushMessage']){
            
            populateFormFromModel(validatedData['pushMessage']);

        }
    
    }
    
    function handlePayloadStep(data){
        if(data.validatedModel != undefined && data.validatedModel.payload != undefined){
            validatedData['payload'] = data.validatedModel.payload;
        }
                
        dynamicComposerContent.html(data.html);
        
        if(validatedData['payload']){
            populateFormFromModel(validatedData['payload']);

        }
    }
    
    function handleRecipientStep(data){
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
    
    
    loadingIndicator.hide();
    errorIndicator.hide();
    updateFormState(steps[currentStepIndex], 'next');



} 

