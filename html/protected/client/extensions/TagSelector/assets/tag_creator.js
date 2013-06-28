jQuery(document).ready(tagCreator);

function tagCreator($){
    var modelTagTable = $("#modelTagTable"),
    tagNameInput = $('#new_tag_form [name="name"]'),
    addItem = function(name, id){
        var row = '<tr name="tagRow"><td> '+ name + '</td>';
        row += '<input value="'+ id +'" type="hidden" name='+ tagSelector_ns.modelName +'[tags][]">';
        row += '<td><span name="deleteTagBtn" class=" btn btn-warning" >remove</span></td>';    
        row +='</tr>';
        
        
        if(  modelTagTable.find('tr[name="tagRow"]').length == 0){
            modelTagTable.find('tbody').after(row);
        }else{
            modelTagTable.find('tr[name="tagRow"]:last').after(row);
        }

    },
    tagTableUpdated = function(e){
        $(this).parents("tr[name='tagRow']").first().remove();
        $.event.trigger({
            type: "tagTableUpdate"
        });
    },
    autoCompleteInit = function(){
        var encodedTypeString = "";
        tagSelector_ns.tagTypes.map(function(type){
            encodedTypeString += "&types[]="+type;
        });
            
        $( "#searchTag" ).autocomplete({
            minLength: 2,
            delay: 50, 
            source: function(request, response){
           
                response(['Loading..']);
           
                var tagFinderUrl = tagSelector_ns.site_url + '/tag/findTag?term=' + request.term + encodedTypeString;
            
                $.get(tagFinderUrl, function(tags){
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
            
            $.event.trigger({
                type: "tagTableUpdate"
            });
            
            return false;
        
        });
    }
    saveTag = function(){
                       
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
                addItem(tag.display_name, tag.id);
        });
    },
    createTagName = function(){
        var value = $(this).val();
        tagNameInput .val(value.toLowerCase().replace(/ /g,"_").replace(/\W/g, ''));
    };
    

   
    
    // event binding
    modelTagTable.on("click", "span[name='deleteTagBtn']", tagTableUpdated);
    $('#new_tag_form [name="display_name"]').keyup(createTagName);
    $("#save_tag_btn").click(saveTag);

    // init
    autoCompleteInit();
   
   
}