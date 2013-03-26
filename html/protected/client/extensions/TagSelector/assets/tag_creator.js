jQuery(document).ready(tagCreator);



function tagCreator($){
    
            
    $("#new_tag_btn").click(function(){
        event.preventDefault();
        
        
        var form = $('#new_tag_form').find('input');
        
        var serializedForm = {
            type: $('[name="type"]').val(),
            name: $('[name="name"]').val(),
            display_name: $('[name="display_name"]').val()
        }
        
        console.log(serializedForm);
        
        $.post('/client/ouroregon/tag/create', { 'Tag' : serializedForm}, function(data) {
        console.log(data);
        });
        
    });

}