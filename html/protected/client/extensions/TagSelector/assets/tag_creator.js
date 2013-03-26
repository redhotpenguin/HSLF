jQuery(document).ready(tagCreator);



function tagCreator($){
    
    $("#save_tag_btn").click(function(){
                 
        var form = $('#new_tag_form').find('input');
       
        var serializedForm = {
            type: $('[name="type"]').val(),
            name: $('[name="name"]').val(),
            display_name: $('[name="display_name"]').val()
        }
                
        $.post('/client/ouroregon/tag/create', {
            'Tag' : serializedForm
        }, function(tag) {
            updateTagTable(tag);
        });
    });
    
    function updateTagTable(tag){
        
        var row = '<tr><td>'+tag.display_name+'</td>';
        
        row += '<td><input value="'+tag.id+'" type="checkbox" name="Organization[tags][]"></td>';
        
        row += '</tr>';
   
        $('#table_tag tr:last').after(row);
        
    }
   
}