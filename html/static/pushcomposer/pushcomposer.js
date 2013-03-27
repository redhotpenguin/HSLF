jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    currentPage = 0,
    steps = ['message', 'recipients', 'action', 'review','thankyou'];
    
    composerNextBtn.click(function(){
        if(currentPage < steps.length)
            currentPage++;
        
        updateForm(steps[currentPage]);
    });
    
    composerBackBtn.click(function(){
        if(currentPage > 0)
            currentPage--;
        
        updateForm(steps[currentPage]);
    });
 
 
    function updateForm(pageName){
        var query ='/client/ouroregon/pushComposer/'+pageName;
        
        
        $.get(query, function(form){
            dynamicComposerContent.html(form);
        })
    }
    
    
    updateForm('message');
} 

