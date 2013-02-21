jQuery(document).ready(organization);


function organization($){
    
    var addContactButton = $("#addContactButton"),
    contacts = $("#contacts"),
    originalDropDown = $("#contactDropDown");
 
    $('.deleteBtn').click(deleteDropDown);

 
    addContactButton.click(function(){
        var clonedDropDown = originalDropDown.clone();

      //  var deleteButton = $('<div>').attr('class', 'span3').attr('id','foobar').text("delete").appendTo(clonedDropDown).click(deleteDropDown);
     
     
     console.log( clonedDropDown.find('.deleteBtn') );
     
     clonedDropDown.find('.deleteBtn').click(deleteDropDown);
     
        contacts.append(clonedDropDown);
     
    });
 
    function deleteDropDown(){
        console.log($(this).parent().remove());
    }
 
 
}