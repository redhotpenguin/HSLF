jQuery(document).ready(ballotItemForm);

jQuery.fn.updateURLUsingName = function() {
    var o = $(this[0]) // It's your element    console.log('update');
    
    // get the year from the publication field
    year_published = date_published_input.val().substr(0, 4);

    // retrieve the ballot item name
    item_name = item_name_input.val();
    
    // skip if ballot item empty
    if(item_name == "")
        return false;
    
    // URL minus the slug
    url = ns.site_url+"/ballot/"+year_published+"/";
   
    filtered_url = filterURL(item_name);
    
    if(filtered_url == 'invalid_url'){
        alert('This url is already taken');
    }else{
        site_url_input.val(filtered_url);
        full_url = url+filtered_url;
        o.html("<a target='_blank' href='"+full_url+"'>"+full_url+"</a>");
    }
};

jQuery.fn.updateURLUsingInput = function() {
    var o = $(this[0]) // It's your element    console.log('update');
    
    // get the year from the publication field
    year_published = date_published_input.val().substr(0, 4);

    // retrieve the ballot site url
    site_url = site_url_input.val();
    
    // skip if site url is empty
    if(site_url == "")
        return false;
    
    // URL minus the slug
    url = ns.site_url+"/ballot/"+year_published+"/";
        

    filtered_url = filterURL(site_url, ns.ballot_id);
    
    if(filtered_url == 'invalid_url'){
        alert('This url is already taken');
    }else{
        site_url_input.val(filtered_url);
        full_url = url+filtered_url;
        o.html("<a target='_blank' href='"+full_url+"'>"+full_url+"</a>");
    }

};

function filterURL(item_name, id){
    id = id || "";

    // ajax request url
    ajax_url ="http://www.voterguide.com/admin/ballotItem/ajax/?a=validateURL&url="+item_name;
    
    if(id != "undefined" && id!="" )
        ajax_url += "&id="+id;
    
    console.log(ajax_url);
    var resp;
    
    jQuery.ajax({
        url:    ajax_url,

        success: function(result) {
            resp = result;
        },
        async:   false
    });  
    
    return resp;
}


function ballotItemForm($){

    site_url_span = $("#dynamic_site_url");
    date_published_input = $("#BallotItem_date_published");
    item_name_input = $("#BallotItem_item");
    site_url_input = $("#BallotItem_url");

    site_url_input.focusout(function(){
        site_url_span.updateURLUsingInput();
    });
    
    
    // only trigger the site url completion using the ballot item name when the site url is not set.
    if(site_url_input.val() == ""){
        item_name_input.focusout(function(){
            site_url_span.updateURLUsingName();
        });
    }
   

    date_published_input.change(function(){
        site_url_span.updateURLUsingInput();
    });
    
    site_url_input.trigger('focusout');
    item_name_input.trigger('focusout');    
    date_published_input.trigger('change');    
}