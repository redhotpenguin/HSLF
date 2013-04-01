jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent")
    var loadingIndicator = $("#loadingIndicator"),
    currentPage = 0,
    steps = ['message',  'action', 'recipients', 'review','thankyou'];
    
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
        var query ='/client/ouroregon/pushComposer/nextStep?pageName='+pageName;
        
        var virtualSessionIdVal = $("#virtualSessionId").val();

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
        
        displayLoadingIndicator();
        $.get(query, data, function(form){
            dynamicComposerContent.html(form);
            hideLoadingIndicator();
        });
   
    }
    
    function displayLoadingIndicator(){
        dynamicComposerContent.hide();
        loadingIndicator.show();
    }
    
    function hideLoadingIndicator(){
        dynamicComposerContent.show();
        loadingIndicator.hide();
    }
    
 
    updateForm('message');
} 

