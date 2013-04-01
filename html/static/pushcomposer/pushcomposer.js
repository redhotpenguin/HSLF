jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
    errorIndicator = $("#errorIndicator");
    currentPage = 0,
    steps = ['message',  'action', 'recipients', 'review','thankyou'];
    
    loadingIndicator.hide();
    errorIndicator.hide();
    
    composerBackBtn.hide();
    
    composerNextBtn.click(function(){
        if(currentPage < steps.length){
            composerBackBtn.show();
            currentPage++;
        }
        if(currentPage == steps.length -1 ){ // thank you page
            composerBackBtn.hide();
            composerNextBtn.hide();
        }
                
        updateForm(steps[currentPage]);
    });
    
    composerBackBtn.click(function(){
        if(currentPage > 0){
            composerNextBtn.show();
            currentPage--;
        }
        if(currentPage == 0){
            composerBackBtn.hide();
        }
        
        updateForm(steps[currentPage]);
    });
 
 
    function updateForm(pageName){
        
        var virtualSessionIdVal = $("#virtualSessionId").val();
        
        var query ='/client/ouroregon/pushComposer/nextStep?pageName='+pageName+'&virtualSessionId='+virtualSessionIdVal;


        var data = {
            virtualSessionId: virtualSessionIdVal
        };
        
        console.log(query);
                
        switch(pageName){

            case 'message':
                break;
                
            case 'action':
                data.message = $("textarea#message").val();
                break;
            
            case 'recipients':
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
            dynamicComposerContent.show();

        }).fail(function(jqXHR, textStatus){
            loadingIndicator.hide();
            errorIndicator.html(jqXHR.responseText).show();
        });
       
   
    }
    
 
    
 
    updateForm('message');
} 

