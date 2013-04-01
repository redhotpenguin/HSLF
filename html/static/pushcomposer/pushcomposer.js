jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
    errorIndicator = $("#errorIndicator");
    currentPage = 0;
    steps = ['message',  'action', 'recipients', 'review','thankyou'];
    
    loadingIndicator.hide();
    errorIndicator.hide();
    
    composerBackBtn.hide();
    
    composerNextBtn.click(function(){
        updateFormState(steps[currentPage]);
    });
    
    composerBackBtn.click(function(){
        updateFormState(steps[currentPage]);
    });
 
 
    function updateFormState(pageName){
        
        /*
         *        if(currentPage < steps.length){
            composerBackBtn.show();
            currentPage++;
        }
        if(currentPage == steps.length -1 ){ // thank you page
            composerBackBtn.hide();
            composerNextBtn.hide();
        }
                
         *
         **/
        
        var virtualSessionIdVal = $("#virtualSessionId").val();
        
        var query ='/client/ouroregon/pushComposer/nextStep?pageName='+pageName+'&virtualSessionId='+virtualSessionIdVal;


        var data = {
            virtualSessionId: virtualSessionIdVal
        };
                        
        switch(pageName){

            case 'message':
                break;
                
            case 'action':
                data.message = $("textarea#message").val();
                break;
            
            case 'recipients':
                data = $("#push_composer").serialize();
                break;
                
            case 'review': // send message
                break;
                
            case 'thankyou':
                break;
             
        }
        
        dynamicComposerContent.hide();
        errorIndicator.hide();
        
        loadingIndicator.show();
        var jqxhr  = jQuery.ajax({
            url:query,
            type:'POST',
            data:data
        }).success(function(result){
            loadingIndicator.hide();
            dynamicComposerContent.html(result);
            errorIndicator.hide();
            composerNextBtn.show();
            dynamicComposerContent.show();
            currentPage++;

        }).fail(function(jqXHR, textStatus){
            loadingIndicator.hide();
            composerNextBtn.hide();
            errorIndicator.html(jqXHR.responseText).show();
        });

    }
    
    updateFormState('message');
} 

