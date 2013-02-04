jQuery(document).ready(function($){
  
    var audience_selector = $("input[name='audience_type']");
    var audience_target = $("#audience_target");
    var confirm_broadcast = $('<label>*Confirm broadcast:</label><input type="checkbox" name="confirm_broadcast">');




    audience_selector.change(function(){
        switch(this.value){
            case 'state_district':
                audience_target.addDistrictTree();
                break;
       
            case 'broadcast':
                audience_target.addConfirmationForm();
                break;
        }
    });

    jQuery.fn.addConfirmationForm = function() {
        var o = $(this[0]);
        o.html( confirm_broadcast );
        return o;
    }


    jQuery.fn.addDistrictTree = function() {
        var o = $(this[0]);
    
        $.ajax({
            url: "/pushNotifications/getTreeView",
            success: function(data) {
                o.html(data);
            }
        });
        return o;
    }

    //simulate event when page is first loaded
    $('input[name=audience_type]:checked').change(); 
});