jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
    errorIndicator = $("#errorIndicator"),
    steps = ['message','payload','recipient','validation','confirmation'],
    currentStepIndex = 0;
    
    
    loadingIndicator.hide();
    errorIndicator.hide();
    
    composerBackBtn.hide();
    
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
        
        
        $.ajax({
            url:query,
            type:'POST',
            data:data
        }).success(function(result){
            
            dynamicComposerContent.html(result.html);
           
            if(result.proceedToNextStep){
                currentStepIndex+=1;   
                updateFormState(steps[currentStepIndex], 'next');
            }
                
           
            
        }).fail(function(jqXHR, textStatus){
            console.log(jqXHR.responseText);
        });

    }
    
    

    
    updateFormState(steps[currentStepIndex], 'next');
    
} 

