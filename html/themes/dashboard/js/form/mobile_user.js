jQuery(document).ready(mobileUser);


function mobileUser($){

    var filterInputs = $('input:text'),
    mobileUserCount = $("#mobile_user_count"),
    mobileUserForm = $("#mobile_user_form"),
    pushBtn = $("#push_btn")

    filterInputs.blur(function(){
        postForm('/admin/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
    $("#device_type").change(function(){
        postForm('/admin/mobileUser/getCount', function(count){
            mobileUserCount.html(count)
        });
    });
    
    pushBtn.click(function(){
        var count = mobileUserCount.text();
        if(count == 0){
            alert("Please change the filters")
        }
      
        var r=confirm("Send a message to " + count +  " users?");


        postForm('/admin/mobileUser/push', function(result){
            console.log(result);
        });
    });
    
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