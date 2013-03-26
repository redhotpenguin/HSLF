jQuery(document).ready(tagCreator);

function tagCreator($){
    
    var tagNameInput = $('#new_tag_form [name="name"]')
    
    $('#new_tag_form [name="display_name"]').keyup(function(){
        var value = $(this).val();
        tagNameInput .val(value.toLowerCase().replace(/ /g,"_").replace(/\W/g, ''));
    });
    
    $("#save_tag_btn").click(function(){
                 
        var form = $('#new_tag_form').find('input');
       
        var serializedForm = {
            type: $('[name="type"]').val(),
            name: $('[name="name"]').val(),
            display_name: $('[name="display_name"]').val()
        }
                
        $.post(tagSelector_ns.site_url+'/tag/create', {
            'Tag' : serializedForm
        }, function(tag) {
            $('[name="display_name"]').val('');
            $('[name="name"]').val('');
            updateTagTable(tag);
        });
    });
    
    function updateTagTable(tag){
        
        var row = '<tr><td>'+tag.display_name+'</td>';
        
        row += '<td><input value="'+tag.id+'" type="checkbox" name='+tagSelector_ns.modelName+'[tags][]"></td>';
        
        row += '</tr>';
   
        $('#table_tag tr:last').after(row);
        
    }
   
}