jQuery(document).ready(function(){
    
    $("#select_all").click(function(){
        $("#tasksForm").find(':checkbox').attr('checked', 'checked');
    });
    
    $("#deselect_all").click(function(){
        $("#tasksForm").find(':checkbox').removeAttr('checked');
    });
});