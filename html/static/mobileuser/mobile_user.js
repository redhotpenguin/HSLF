jQuery(document).ready(mobileUser);


function mobileUser($){
    var 
    filterInputs = $('input:text'),
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    pushOnlyCheckBox = $("#push_only_checkbox");
    resultBox =   $("#push_result"),
    addDistrictBtn = $("#add_district_btn"),
    deleteDistrictSpan = $("#delete_district_original");

    
    filterInputs.live('blur', function(){
        submitForm(ns.action_url + '/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
    $("#device_type").change(function(){
        updateCount();
    });
    
    pushOnlyCheckBox.change(function(){
        updateCount();
    });
   

    function updateCount(){
        submitForm(  ns.action_url +  '/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    }
    
    
    $("#export_btn").click(function(){
     
        var serializedForm = mobileUserForm.serialize();
     
        window.location = ns.action_url + '/mobileUser/export?'+serializedForm;

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