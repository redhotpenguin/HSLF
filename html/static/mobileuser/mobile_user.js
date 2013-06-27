jQuery(document).ready(mobileUser);


function mobileUser($){
    var 
    filterInputs = $('input:text'),
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    pushOnlyCheckBox = $("#push_only_checkbox"),
    updateCount = function(){
        submitForm(  ns.action_url +  '/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    },
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
    }
    
    // event binding
    $("#device_type").change(updateCount);
    pushOnlyCheckBox.change(updateCount);
    
        
    $("#export_btn").click(function(){
        var serializedForm = mobileUserForm.serialize();
        window.location = ns.action_url + '/mobileUser/export?'+serializedForm;
    });
    
    filterInputs.live('blur', function(){
        submitForm(ns.action_url + '/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
}