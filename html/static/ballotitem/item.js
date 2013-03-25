jQuery(document).ready(itemForm);

jQuery.fn.updateURLUsingName = function() {
    var o = $(this[0]),
    item_name_input = $("#Item_item"),

    // retrieve the item name
    item_name = item_name_input.val();
    
    // skip if item name empty
    if(item_name == "")
        return false;
    
    
    // filter the url
    filtered_url = filterURL(item_name, '', function(filtered_url){
        o.printURL(filtered_url);
    });
       
  
};

jQuery.fn.updateURLUsingInput = function() {
    var o = $(this[0]),
    site_url_input = $("#Item_slug"),

    
    // retrieve the item site url
    site_url = site_url_input.val();
    
    // skip if site url is empty
    if(site_url == "")
        return false;
   
    // filter the url
    filterURL(site_url, ns.item_id, function(filtered_url){
        o.printURL(filtered_url);
    });
    
   
};

jQuery.fn.printURL = function(filtered_url) {
    var date_published_input = $("#Item_date_published"),
    site_url_input = $("#Item_slug");

    // get the year from the publication field
    var  year_published = date_published_input.val().substr(0, 4);
    
    // URL minus the slug
    var url = ns.share_url+"/#!/items/";
   
    var o = $(this[0]);
        
    if(filtered_url == 'invalid_url'){
        o.html("<span class='errorMessage'>Slug already used.</span>");
    }else{
        site_url_input.val(filtered_url);
        full_url = url+filtered_url;
        o.html("<a target='_blank' href='"+full_url+"'>"+full_url+"</a>");
    }
}


// synchrously make a request to the server to filter the url
function filterURL(item_name, id, _cb){
    id = id || "";

    // ajax request url
    ajax_url = ns.site_url+ "/ballotItem/ajax?a=validateURL&url="+item_name;
    if(id != "undefined" && id!="" )
        ajax_url += "&id="+id;

    jQuery.ajax({
        url:    ajax_url,

        success: function(result) {
            _cb(result);
        },
        async:   true
    });  
    
  
}

// executed when the page is Ready
function itemForm($){

    var site_url_span = $("#dynamic_site_url"),
    date_published_input = $("#Item_date_published"),
    item_name_input = $("#Item_item"),
    site_url_input = $("#Item_slug"),
    item_item_type = $("#Item_item_type");
    candidate_related_inputs = $("#candidate_related_inputs");
    measure_related_inputs = $("#measure_related_inputs");
    

    site_url_input.focusout(function(){
        site_url_span.updateURLUsingInput();
    });
    
    // only trigger the site url completion using the  item name when the site url is not set.
    if(site_url_input.val() == ""){
        item_name_input.focusout(function(){
            site_url_span.updateURLUsingName();
        });
    }
   
    date_published_input.change(function(){
        site_url_span.updateURLUsingInput();
    });
    
    
    item_item_type.change(function(){
        var item_type = this.value;
        
        if(item_type == 'candidate'){
            candidate_related_inputs.show();
            measure_related_inputs.hide();
        }else{ // measure
            candidate_related_inputs.hide();
            measure_related_inputs.show();

        }
    });
    
    item_item_type.trigger('change');
    
    
    // only trigger the events on page load when a new item is created
    if(!ns.item_id){
        item_name_input.trigger('focusout');    
        date_published_input.trigger('change'); 
    }else{
        site_url_input.attr('readonly', true);
        site_url_input.attr('title', "Click to change this url");
        site_url_input.click(function(){
            site_url_input.attr('readonly', false);
        });
        site_url_input.focusout(function(){
            site_url_input.attr('readonly', true);
        });
   
    }
    site_url_input.trigger('focusout');
    
    
    // for new  items. store the detail in the browser and inject it if the user accidently reloads or leave the page
    if(!ns.item_id){ 
        
        if($.browser.msie  && parseInt($.browser.version, 10) === 8) // exclude ie8
            return;
        
    
        textarea = $('#Item_detail');
        
        content = textarea.val();

        $(window).bind('beforeunload', function(){
            content = textarea.text();
            if(!content == "")
                sessionStorage.ItemContent = content;
        });
    
        if(sessionStorage.ItemContent){
        // textarea.text(sessionStorage.ItemContent);
        }

    }
   
 
} //  ready function  end

