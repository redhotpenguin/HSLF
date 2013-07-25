jQuery(document).ready(contact);


function contact($){
        
    // contact selector
    var  deleteContact = function (){
        $(this).parent().remove();
    },
    addContactRow = function(name, id){
        
        var contactRow = $("<div>");
        contactRow.addClass('contactRow');
        
        contactRow.append("<div class='contactName'> " + name + " </div>  <input type='hidden' name = 'Organization[contacts][]' value='"+id+"'/>");
       
        $("#contacts").append(contactRow);
        
    },
    contactSelected = function(e){        
        var contactName = e.target.options[e.target.selectedIndex].text,
            contactId = e.target.options[e.target.selectedIndex].value;
           
        addContactRow(contactName, contactId);
    };
    // contact creator
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
            }, function(e){
               // updateContactList();
                addContactRow( data.first_name + " " + data.last_name, data.id );
                $("#close_btn").click();
            });
        }
    },
    showError = function (errorMessage){
        $("#contact_error").html(errorMessage).show();  
    };
 
    // event binding
    $( "#contacts" ).sortable();
    $("#save_contact_btn").click(saveContact);
    $("#ContactDropdown").change(contactSelected);

}