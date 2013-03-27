jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    currentPage = 0,
    steps = ['message', 'recipients', 'action', 'review','thankyou'];
    
    composerNextBtn.click(function(){
        if(currentPage < steps.length)
            currentPage++;
        
        updateForm(steps[currentPage -1 ]);
    });
 
 
    function updateForm(pageName){
        var query ='/client/ouroregon/pushComposer/'+pageName;
        
        
        $.get(query, function(form){
           dynamicComposerContent.html(form);
        })
    }
} 

