jQuery(document).ready(contact);


function contact($){
        
    // build a contact dropdown
    var contactList = "";
    $.each(contactSelector_ns.contactList, function (contactId, contactName) {
        contactList += "<option value='" + contactId + "'>" + contactName + "</option>";
    });
    
    var contactDropDown = $("<div class='row-fluid'><select style='float:left;' class='span6 ' name='"+contactSelector_ns.dropDownName+"'>"+contactList+"</select></div>");

    $('.deleteBtn').click(deleteDropDown);

 
    $("#addContactButton").click(function(){

        var clonedDropDown = contactDropDown.clone();
        
         var deleteButton = $('<div>').attr('class', 'span3 btn btn-warning').attr('id','foobar').text("delete").appendTo(clonedDropDown).click(deleteDropDown);
     
     
        console.log( clonedDropDown );
     
       clonedDropDown.find('.deleteBtn').click(deleteDropDown);
     
        $("#contacts").append(clonedDropDown);
     
    });
 
    function deleteDropDown(){
        console.log($(this).parent().remove());
    }
 
 
}