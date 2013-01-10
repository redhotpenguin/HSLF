jQuery(document).ready(mobileUser);


function mobileUser($){
    
    var 
    filters = $("#filters"),
    composer = $("#composer"),
    filterInputs = $('input:text'),
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    sendAlertBtn = $("#send_alert_btn"),
    addTagBtn = $("#add_tag_btn"),
    deleteTagSpan = $("#delete_tag_original")

    
    filterInputs.live('blur', function(){
        postForm('/admin/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
    $("#device_type").change(function(){
        updateCount();
    });

    sendAlertBtn.click(function(){
        filters.hide(100);
        composer.show(100);
    });
    
    
    
    addTagBtn.click(function(){
       
        var clonedTagBoxCount = $("#tag_list .tagBox").length;
        
        var newTagBox =   $("#original_tag").clone().attr("id", "tagBox"+clonedTagBoxCount);
      
        
        var tagInput = newTagBox.find(".tagInput");
        
        tagInput.val("")
        tagInput.attr("id", "");
        newTagBox.append(deleteTagSpan.clone().css("display", "inline").click(deleteTagBox));
       
        
        $("#tag_list").append(newTagBox);
    });
    
    $("#cancel_alert_btn").click(function(){
        filters.show(100);
        composer.hide(100);
    });
    
    function deleteTagBox(ev){
        $(this).parent(".tagBox").remove();
        updateCount();
    }
    
    function updateCount(){
        console.log('updating count');
        postForm('/admin/mobileUser/getCount', function(count){
            console.log('new count: ' + count);
            mobileUserCount.html(count)
        });
    }
    
    function postForm(actionUrl, _cb){         
        jQuery.ajax({
            url:    actionUrl,
            data: mobileUserForm.serialize(),
            type: 'GET',
            
            success: function(result) {
                _cb(result)
            },
            async:   true
        });  
    }
}