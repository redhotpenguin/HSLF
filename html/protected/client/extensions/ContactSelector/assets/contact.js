jQuery(document).ready(contact);


function contact($){
        
    // contact selector
    var addContactRow = function(name, id){
        if(id == void 0 || id <= 0)
            return;
        
        var contactRow = $("<tr>");
        contactRow.addClass('contactRow')
        
        var html = "<td class='contactName'>" + name + "</td> ";
        html+= '<td><span  name="deleteContactBtn" class="btn btn-warning" >Remove</span>';
        html+="<input type='hidden' name='" + contactSelector_ns.className + "[contacts][]' value='"+id+"'/></td>";
        
        contactRow.append(html);
       
        $("#contacts").append(contactRow);
        
    },
    removeContactRow = function(){
        $(this).closest('.contactRow').remove();
    },
    contactSelected = function(e){
        var contactName = e.target.options[e.target.selectedIndex].text,
        contactId = e.target.options[e.target.selectedIndex].value;
           
        addContactRow(contactName, contactId);
    },
    // contact creator
    clearFormInput = function(){
        $('[name="first_name"]').val('');
        lastName = $('[name="last_name"]').val('');
        email = $('[name="email"]').val('');
        title = $('[name="title"]').val('');
        phoneNumber = $('[name="phone_number"]').val('');
    },
    saveContact = function(){
        var firstName = $('[name="first_name"]').val(),
        lastName = $('[name="last_name"]').val(),
        email = $('[name="email"]').val(),
        title = $('[name="title"]').val(),
        phoneNumber = $('[name="phone_number"]').val();

        var data = {
            first_name: firstName,
            last_name: lastName,
            email: email,
            title: title,
            phone_number: phoneNumber
        },
        valid = true,
        emailPattern  =  /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
      
        if(!firstName){
            valid = false;
            showError('First Name cannot be blank.');
        }
        
        if(email.length > 0 && !email.match(emailPattern)){
            valid = false;
            showError('Email is not a valid email address.');
        }
         
        if(valid){
            $.post( contactSelector_ns.site_url +  '/contact/create', {
                'Contact': data
            }, function(result){
                clearFormInput();
                clearErrors();
                
                var contactDropdown = document.getElementById('contactDropdown'),  
                option = document.createElement("option");
                    
                option.text = result.first_name + " " + result.last_name;
                option.value = result.id;                
                contactDropdown.add(option)
                
                addContactRow( result.first_name + " " + result.last_name, result.id );
                $("#close_btn").click();
            });
        }
    },
    clearErrors = function(){
        $("#contact_error").html('').hide();
    },
    showError = function (errorMessage){
        $("#contact_error").html(errorMessage).show();  
    };
 
    // event binding
    $("#save_contact_btn").click(saveContact);
    $("#contactDropdown").change(contactSelected);
    $("#contacts").on("click", "span[name='deleteContactBtn']", removeContactRow);
    
    // jquery-ui magic
    // Return a helper with preserved width of cells
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $("#contacts tbody").sortable({
        helper: fixHelper,
        distance: 5,
        axis: 'y' 
    }).disableSelection();
    
}