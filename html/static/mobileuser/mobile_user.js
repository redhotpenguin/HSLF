jQuery(document).ready(mobileUser);


function mobileUser($){
    var 
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    pushOnlyCheckBox = $("#push_only_checkbox"),
    submitForm = function(actionUrl, _cb, method){  
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
    },
    updateUserCount = function(){
        submitForm(ns.action_url + '/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    };
    
    // event binding
    $("#device_type").change(updateUserCount);
    pushOnlyCheckBox.change(updateUserCount);
    
        
    $("#export_btn").click(function(){
        var serializedForm = mobileUserForm.serialize();
        window.location = ns.action_url + '/mobileUser/export?'+serializedForm;
    });
        
    $(document).on("tagTableUpdate", function(){
            updateUserCount();
    });
    
    
}