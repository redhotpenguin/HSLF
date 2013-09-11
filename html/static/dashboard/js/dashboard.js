jQuery(document).ready(init);

function init($){
    $('.update_box.btn-success').fadeTo(5000, 0.00, function(){ //fade
        $(this).slideUp(500, function() { //slide up
            $(this).remove(); //then remove from the DOM
        });
    });
}

