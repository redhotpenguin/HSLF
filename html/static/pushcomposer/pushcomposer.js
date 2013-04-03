jQuery(document).ready(composer);


function composer($){
    
    var composerNextBtn = $("#composerNextBtn"),
    composerBackBtn = $("#composerBackBtn"),
    dynamicComposerContent = $("#dynamicComposerContent"),
    loadingIndicator = $("#loadingIndicator"),
    errorIndicator = $("#errorIndicator")
    deleteTagSpan = $("#delete_tag_original"),
    addTagBtn = $("#add_tag_btn");
    
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
    
    
    
    addTagBtn.live('click',function(){
        console.log("test");
        var clonedTagBoxCount = $("#tag_list .tagBox").length;
        
        var newTagBox =   $("#original_tag").clone().attr("id", "tagBox"+clonedTagBoxCount);
      
        
        var tagInput = newTagBox.find(".tagInput");
        
        tagInput.val("")
        tagInput.attr("id", "");
        newTagBox.append(deleteTagSpan.clone().css("display", "inline").click(deleteTagBox));
       
        
        $("#tag_list").append(newTagBox);
    });
    
    function deleteTagBox(ev){
        $(this).parent(".tagBox").remove();
        updateCount();
    }
    
    updateFormState();
    
} 

