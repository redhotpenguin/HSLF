<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxFileUploader extends CWidget {

    public $options = array();
    public $model;
    public $attribute;

    public function start() {
        $attribute = $this->attribute;

        $img_url = $this->model->$attribute;

        if ($img_url)
            $this->displayThumb($img_url);

        $this->displayModalUploaderButton();
    }

    public function init() {
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();


        $cs->registerScriptFile($baseUrl . '/js/form/jquery.form.js');
    }

    private function displayThumb($image_url) {
        echo '<div><a target="_blank" href="' . $image_url . '"><img id="' . $this->attribute . '_image_preview" src="' . $image_url . '" style="width:50px; height:50px; "/></a></div>';
    }

    private function displayModalUploaderButton() {
        ?>


        <div class="alignLeft">
            <?php echo CHtml::activeTextField($this->model, $this->attribute, array('id' => 'upload_insert_image')); ?>
        </div>

        <div style="margin-left:5px;" class="alignLeft">
            <input type="button" id="opener" class="btn btn-primary" value="Upload"></input>
        </div>


        <script type="text/javascript">
                                                                                                                                                                
            jQuery(document).ready(function($){
                                                                        
                image_dialog =  $("#dialog").dialog({
                    autoOpen: false,
                    modal: true,
                    height: 450,
                    width: 700,
                    title: "Upload an image",
                    buttons:{
                        Close: function() {
                            $( this ).dialog( "close" );
                        } 
                    }
                });
                                                                                                                                         
                $('#opener').click(function(){           
                    image_dialog.load('<?php echo $this->options['modal_view']; ?>' ).dialog("open");                                   
                });
            });
                                                                                                                                                        
        </script>


        <div id="dialog">loading</div>

        <?php
    }

    public function modalWindow() {
        $this->render('ModalWindow');
    }

}