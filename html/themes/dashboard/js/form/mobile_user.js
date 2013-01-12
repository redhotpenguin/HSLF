jQuery(document).ready(mobileUser);


function mobileUser($){
    
    var 
    filters = $("#filters"),
    composer = $("#composer"),
    composerInput = $("#composer_input");
    filterInputs = $('input:text'),
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    composerBtn = $("#compose_alert_btn"),
    addTagBtn = $("#add_tag_btn"),
    addOptionBtn = $("#add_option_btn"),
    deleteTagSpan = $("#delete_tag_original"),
    send_alert_btn = $("#send_alert_btn"),
    pushOnlyCheckBox = $("#push_only_checkbox");
    resultBox =   $("#push_result");


    
    filterInputs.live('blur', function(){
        submitForm('/admin/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
    $("#device_type").change(function(){
        updateCount();
    });
    
    pushOnlyCheckBox.change(function(){
        updateCount();
    });
    

    composerBtn.click(function(){
        pushOnlyCheckBox.attr('checked', 'checked');
        pushOnlyCheckBox.trigger('change');
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
        composerInput.val("");
        resultBox.html("");
        resultBox.hide();
        
        $.each($("#key_value_list").find(':input'), function(){
            $(this).val(""); 
        });
    });
    
    
    send_alert_btn.click(function(){
        
        submitForm('/admin/mobileUser/sendAlert', function(result){
            resultBox.show(100);
            
            
            if(result == "success"){
                resultBox.html("<div class='alert alert-success'>Message successfuly sent.</div>");
                $("#alert").val('');
            }
            else{
                resultBox.html("<div class='alert alert-error'>Error: "+result+"</div>");
            }
          
        }, 'POST');
            
    });
    
    function deleteTagBox(ev){
        $(this).parent(".tagBox").remove();
        updateCount();
    }
    
    function updateCount(){
        console.log('updating count');
        submitForm('/admin/mobileUser/getCount', function(count){
            console.log('new count: ' + count);
            mobileUserCount.html(count)
        });
    }
    
    
    addOptionBtn.click(function(){
        var clonedKeyValueBoxCount = $("#key_value_list .keyValueBox").length;
        
        var newKeyValueBox =   $("#original_key_value").clone().attr("id", "keyValueBox"+clonedKeyValueBoxCount);
      
       
        var keyInput = newKeyValueBox.find(".keyInput");
        keyInput.val("")
        keyInput.attr("id", "");
        
        var valueInput = newKeyValueBox.find(".valueInput");
        valueInput.val("")
        valueInput.attr("id", "");
        
        
        newKeyValueBox.append(deleteTagSpan.clone().css("display", "inline").click(function(){
            $(this).parent(".keyValueBox").remove();

        }));
       
        
        $("#key_value_list").append(newKeyValueBox);
    });
    
    
    $("#export_btn").click(function(){
     
        var serializedForm = mobileUserForm.serialize();
     
        window.location = "/admin/mobileUser/export?"+serializedForm;


    /*submitForm('/admin/mobileUser/export', function(result){
            console.log(result);
            
            
        }, 'GET');*/
    });
    
    function submitForm(actionUrl, _cb, method){  
        if(method == 'undefined')
            method = 'GET';

        jQuery.ajax({
            url:    actionUrl,
            data: mobileUserForm.serialize(),
            type: method,
            
            success: function(result) {
                _cb(result)
            },
            async:   true
        });  
    }
}