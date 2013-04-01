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
    
    composerNextBtn.live('click',function(){
        updateFormState('next');
    });
    
    composerBackBtn.live('click',function(){
        updateFormState('back');
    });
 
 
    function updateFormState(direction){

        var virtualSessionIdVal = $("#virtualSessionId").val();
        
        var query ='/client/ouroregon/pushComposer/step?virtualSessionId='+virtualSessionIdVal+"&direction="+direction;

        var data = {
            virtualSessionId: virtualSessionIdVal
        };
                        
        data = $("#push_composer").serialize();
        
        dynamicComposerContent.hide();
        errorIndicator.hide();
        
        loadingIndicator.show();
        $.ajax({
            url:query,
            type:'POST',
            data:data
        }).success(function(result){
            loadingIndicator.hide();
            dynamicComposerContent.html(result);
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

