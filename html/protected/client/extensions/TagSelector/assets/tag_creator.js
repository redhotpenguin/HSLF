jQuery(document).ready(tagCreator);

function tagCreator($){
    /*
    var tagNameInput = $('#new_tag_form [name="name"]')
    
    $('#new_tag_form [name="display_name"]').keyup(function(){
        var value = $(this).val();
        tagNameInput .val(value.toLowerCase().replace(/ /g,"_").replace(/\W/g, ''));
    });
    
    $("#save_tag_btn").click(function(){
                 
        var tagType = $('[name="type"]').val(),
        tagName = $('[name="name"]').val(),
        tagDisplayName = $('[name="display_name"]').val();
        
        if(!tagName || !tagDisplayName){
            return;
        }
        
        var serializedForm = {
            type: tagType,
            name: tagName,
            display_name: tagDisplayName
        }
                
        $.post(tagSelector_ns.site_url+'/tag/create', {
            'Tag' : serializedForm
        }, function(tag) {
            $('[name="display_name"]').val('');
            $('[name="name"]').val('');
            if(tag.id != undefined)
                updateTagTable(tag);
        });
    });
    
    function updateTagTable(tag){
        
        var row = '<tr><td>'+tag.display_name+'</td>';
        
        row += '<td><input value="'+tag.id+'" type="checkbox" name='+tagSelector_ns.modelName+'[tags][]"></td>';
        
        row += '</tr>';
   
        $('#table_tag tr:last').after(row);
        
    }
   */
  
    var modelTagTable = $("#modelTagTable"),
    addItem = function(name, id){
        var row = '<tr><td> '+ name + '</td><td><input value="'+ id +'" type="text" name='+ tagSelector_ns.modelName +'[tags][]"></td></tr>';
        modelTagTable.find('tr:last').after(row);
    };
    

    $( "#searchTag" ).autocomplete({
        minLength: 2,
        delay: 50, 
        source: function(request, response){
            
            $.get('http://www.voterguide.com/client/afscme/tag/findTag?term=' + request.term, function(tags){
                response( tags.map(function(tag){
                    return {
                        label: tag.display_name,
                        value: tag.id
                    }
                }));
                
            });
        }

    }).on("autocompleteselect", function(event, ui){
        addItem( ui.item.label, ui.item.value );
        $(this).val('');
        return false;
        
    });
   
   
   
   
}