<?php

class SliderWidget extends CWidget {

    public $options = array();
    public $model;
    public $attribute;
    private $slider_id;
    private $input_id;

    public function init() {
 
        if (!isset($this->options['min']))
            $this->options['min'] = 1;

        if (!isset($this->options['max']))
            $this->options['max'] = 100;

        if (!isset($this->options['orientation']))
            $this->options['orientation'] = 'horizontal';

        if (!isset($this->options['step']))
            $this->options['step'] = 1;

        if (!isset($this->options['animate']))
            $this->options['animate'] = true;
        
        if (!isset($this->options['width']))
            $this->options['width'] = 100;


        $this->slider_id = 'slider_' . $this->attribute;
        $this->input_id = 'input_' . $this->attribute;
        $this->publishAssets();
    }

    protected function publishAssets() {
        
        $js = Yii::app()->getClientScript();

        $js->registerScript('slider-widget-javascript-'.$this->attribute, $this->sliderScript(), CClientScript::POS_BEGIN);
    }

    public function run() {
        $this->displayInputField();
        $this->displaySlider();
    }

    private function displayInputField() {

        echo CHtml::activeTextField($this->model, $this->attribute, array('size' => 50, 'maxlength' => 2048, 'id' => $this->input_id));
    }

    private function displaySlider() {
        echo '<div id="' . $this->slider_id . '" style="width:'.$this->options['width'].'px;"></div>';
    }

    private function sliderScript() {
        ob_start();
        ?>
        jQuery(document).ready(function(){

        $(function() {
        $( "#<?php echo $this->slider_id; ?>" ).slider({
        range: "min",
        animate:<?php echo ( $this->options['animate'] == true ? 'true': 'false' ); ?>,
        value: $( "#<?php echo $this->input_id; ?>" ).val(),
        min: <?php echo $this->options['min']; ?>,
        max: <?php echo $this->options['max']; ?>,
        orientation:'<?php echo $this->options['orientation']; ?>',
        step: <?php echo $this->options['step']; ?>,
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


        if(value > <?php echo $this->options['max']; ?>)
        value = <?php echo $this->options['max']; ?>;

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
