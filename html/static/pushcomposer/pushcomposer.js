jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
    errorIndicator = $("#errorIndicator");
    
    loadingIndicator.hide();
    errorIndicator.hide();
    
    composerBackBtn.hide();
    
    composerNextBtn.click(function(){
        updateFormState();
    });
    
    composerBackBtn.click(function(){
        updateFormState();
    });
 
 
    function updateFormState(){
       
        
        var virtualSessionIdVal = $("#virtualSessionId").val();
        
        var query ='/client/ouroregon/pushComposer/nextStep?virtualSessionId='+virtualSessionIdVal;


        var data = {
            virtualSessionId: virtualSessionIdVal
        };
                        
        data = $("#push_composer").serialize();
        
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
        }).fail(function(jqXHR, textStatus){
            loadingIndicator.hide();
            composerNextBtn.hide();
            errorIndicator.html(jqXHR.responseText).show();
        });

    }
    
    updateFormState();
} 

