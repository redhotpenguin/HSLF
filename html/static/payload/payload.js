jQuery(document).ready(form);

// executed when the page is ready
function form($){
    var
    payload_type = $("#Payload_type");
    post_related_inputs = $("#post_related_inputs");
    share_related_inputs = $("#share_related_inputs");
    
    share_related_inputs.hide();
    post_related_inputs.hide();


    payload_type.change(function(){

        
        var type = this.value;
        
        if(type == 'share'){
            share_related_inputs.show();
            post_related_inputs.hide();
        }else if(type == 'post'){
            post_related_inputs.show();
            share_related_inputs.hide();
        }else{
            post_related_inputs.hide();
            share_related_inputs.hide();
        }
    });
    
 payload_type.trigger('change');
   

 
} //  ready function  end

