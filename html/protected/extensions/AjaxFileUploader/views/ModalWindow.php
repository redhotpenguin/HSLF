<form id="myForm" method="post" action="<?php echo $this->options['upload_handler']; ?>" enctype="multipart/form-data">
    <fieldset>
        <p>Size must be 280x320</p>
        <input id="image_selector" type="file" name="image_url" />    <br/>
    </fieldset>
</form>

<div id="image_upload_response"></div>

<script>
    
    options = {
        success:  showResponse  // post-submit callback 
    };
    $('#myForm').ajaxForm(options); 
      
    $("#image_selector").change( function(){
         $('#myForm').submit();
    });
      
    function showResponse(responseText, statusText, xhr, $form){

        target = $("#image_upload_response");

        
        if(responseText == "")
            return false;
        
        target.html("<img src='"+responseText+"' /> <br/><br/>" );
  
        use_img_btn = $('<div class="btn btn-success" id="use_img">Use this image</div>');
        target.append(use_img_btn);
          
        use_img_btn.click(function(){
            $("#<?php echo $this->attribute;?>_image_preview").attr('src', responseText);
             
            $("#upload_insert_image").val(responseText);
             image_dialog =  $("#dialog").dialog('close');
        });
          
        
    };
    
</script>