<?php

class SliderWidget extends CWidget {

    public $options = array();
    public $model;
    public $attribute;
    private $slider_id;
    private $input_id;

    public function init() {
        $this->slider_id = 'slider_' . $this->attribute;
        $this->input_id = 'input_' . $this->attribute;
        $this->publishAssets();
    }

    protected function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/slider.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception('SliderWidget - Error: Couldn\'t find assets to publish.');
        }

        $js = Yii::app()->getClientScript();

        $js->registerScript('slider-widget-javascript-id', $this->sliderScript(), CClientScript::POS_BEGIN);
    }

    public function run() {
        $this->displayInputField();
        $this->displaySlider();
    }

    private function displayInputField() {

        echo CHtml::activeTextField($this->model, 'score', array('size' => 50, 'maxlength' => 2048, 'id' => $this->input_id));
    }

    private function displaySlider() {
        echo '<div id="' . $this->slider_id . '" style="width:210px;"></div>';
    }

    private function sliderScript() {
        ob_start();
        ?>
            jQuery(document).ready(function(){
                                                	
                $(function() {
                    $( "#<?php echo $this->slider_id; ?>" ).slider({
                        range: "min",
                        value: $( "#<?php echo $this->input_id; ?>" ).val(),
                        min: 1,
                        max: 101,
                        slide: function( event, ui ) {
                            $( "#<?php echo $this->input_id; ?>" ).val( parseInt(ui.value) );	
                        }
                    });
                                                		
                    $( "#<?php echo $this->input_id; ?>" ).val(  $( "#<?php echo $this->slider_id; ?>" ).slider( "value" ) );
                });
                                                	
                           	
                $("#<?php echo $this->input_id; ?>").keyup(function(event){
                                                	
                                                        
                    value = parseInt( event.currentTarget.value ) ;
                                                		
                    if(value == "")
                        return false;
                                                			

                    if(value> 100)
                        value = '101';
                                                			
                    event.currentTarget.value = value;
                                                                    
                                                		
                    $( "#<?php echo $this->slider_id; ?>" ).slider('value', value);
                                                		
                });
                                                	
            });
        <?php
        $script = ob_get_contents();
        ob_end_clean();

        return $script;
    }

}
?>
