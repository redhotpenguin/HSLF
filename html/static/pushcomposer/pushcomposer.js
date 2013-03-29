jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    currentPage = 0,
    steps = ['message', 'recipients', 'action', 'review','thankyou'];
    
    composerBackBtn.hide();
    
    composerNextBtn.click(function(){
        if(currentPage < steps.length){
            composerBackBtn.show();
            currentPage++;
        }
        
        updateForm(steps[currentPage]);
    });
    
    composerBackBtn.click(function(){
        if(currentPage > 0){
            currentPage--;
        }
        if(currentPage == 0){
            composerBackBtn.hide();
        }
        
        updateForm(steps[currentPage]);
    });
 
 
    function updateForm(pageName){
        var query ='/client/ouroregon/pushComposer/'+pageName;
        
        var virtualSessionIdVal = $("#virtualSessionId").val();

                
        switch(pageName){
            
            case 'message':
                $.get(query, {virtualSessionId: virtualSessionIdVal}, function(form){
                    dynamicComposerContent.html(form);
                });
                break;
            
            case 'recipients': // send message
                var messageVal = $("textarea#message").val();     
                $.get(query, {
                    message: messageVal,
                    virtualSessionId: virtualSessionIdVal
                }, function(form){
                    dynamicComposerContent.html(form);
                });
                break;
             
            default:
                $.get(query, function(form){
                    dynamicComposerContent.html(form);
                });
                break;
        }
        
        
      
    }
    
 
    updateForm('message');
} 

