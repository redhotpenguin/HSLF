jQuery(document).ready(ballotItemForm);

jQuery.fn.updateURLUsingName = function() {
    var o = $(this[0]); // It's your element    console.log('update');
    
    // retrieve the ballot item name
    item_name = item_name_input.val();
    
    // skip if ballot item name empty
    if(item_name == "")
        return false;
    
    
    // filter the url
    filtered_url = filterURL(item_name);
       
    o.printURL();
};

jQuery.fn.updateURLUsingInput = function() {
    var o = $(this[0]); // It's your element    console.log('update');
    
    // retrieve the ballot site url
    site_url = site_url_input.val();
    
    // skip if site url is empty
    if(site_url == "")
        return false;
   
    // filter the url
    filtered_url = filterURL(site_url, ns.ballot_id);
    
    o.printURL();
    
};

jQuery.fn.printURL = function() {
 
    // get the year from the publication field
    year_published = date_published_input.val().substr(0, 4);
    
    // URL minus the slug
    url = ns.site_url+"/ballot/"+year_published+"/";
    
    
    var o = $(this[0]);
        
    if(filtered_url == 'invalid_url'){
        o.html("<span class='errorMessage'>This URL is already being used.</span>");
    }else{
        site_url_input.val(filtered_url);
        full_url = url+filtered_url;
        o.html("<a target='_blank' href='"+full_url+"'>"+full_url+"</a>");
    }
}


// synchrously make a request to the server to filter the url
function filterURL(item_name, id){
    id = id || "";

    // ajax request url
    ajax_url = ns.site_url+ "/admin/ballotItem/ajax/?a=validateURL&url="+item_name;
    
    if(id != "undefined" && id!="" )
        ajax_url += "&id="+id;
    
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

// executed when the page is Ready
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
    
    // only trigger the events on page load when a new item is created
    if(!ns.ballot_id){
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
    
    
    // for new ballot items. store the detail in the browser and inject it if the user accidently reloads or leave the page
    if(!ns.ballot_id){ 
        
        if($.browser.msie  && parseInt($.browser.version, 10) === 8) // exclude ie8
            return;
        
    
        textarea = $('#BallotItem_detail');
        
        content = textarea.val();

        $(window).bind('beforeunload', function(){
            content = textarea.text();
            if(!content == "")
                sessionStorage.BallotItemContent = content;
        });
    
        if(sessionStorage.BallotItemContent){
           // textarea.text(sessionStorage.BallotItemContent);
        }
        


    }
    

}
