<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TinyMce
 *
 * @author Jonas Palmero
 */
class TinyMce extends CInputWidget {

    public $options = array();
    public $htmlOptions;

    public function init() {
        $this->publishAssets();
    }

    protected static function publishAssets() {

        Yii::app()->clientScript->registerCoreScript('jquery');

        /*
         * bypass Yii's assets system
         * because tincemce has way too many files to load
         * which causes issues when using s3
         */
        $cs = Yii::app()->getClientScript();
        $baseUrl = Yii::app()->baseUrl;
        $cs->registerScriptFile($baseUrl . '/static/tinymce/jscripts/tiny_mce/jquery.tinymce.js');
    }

    public function run() {

        $baseUrl = Yii::app()->baseUrl;

        list($name, $id) = $this->resolveNameID();

        // theme found at http://tinymce.swis.nl/demo
        ?>


        <script type="text/javascript">
            $().ready(function() {
                $('#<?php echo $id ?>').tinymce({
                    // Location of TinyMCE script
                    script_url : '<?php echo $baseUrl . '/static/tinymce/jscripts/tiny_mce/'; ?>tiny_mce.js',
                    elements:'<?php echo $id; ?>',
                    mode:'textareas',
                    theme : "ribbon",
                    inlinepopups_skin : 'ribbon_popup',                                                                                                   
                    plugins : "tableextras",                                                                                           
                    theme_advanced_statusbar_location  : "none",
                    theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px, 22px",
                    theme_advanced_toolbar_align : "left",                                             
                    relative_urls : false,
                    convert_urls : false,       
                    theme_ribbon_tab1 : {   title : "HTML",
                        items : [
                            ["link","|","fontselect","|","fontsizeselect"], 
                            ["justifyleft,justifycenter,justifyright,justifyfull",
                                "bullist,numlist",
                                "|",     
                                "bold,italic,underline",
                                "outdent,indent"], 
                            ["paragraph", "heading1", "heading2", "heading3"],
                            ["search", "|", "replace", "|", "paste"]]
                    },
                                                                                                                                                    

                                                                                                                                                    
                    theme_ribbon_tab2 : { },
                                                                                                                                
                    theme_ribbon_tab3 : {   title : "Source",
                        source : true                                                                                       
                    },                                                                                                                                           
                    content_css : "<?php echo $baseUrl . '/static/tinymce/css/'; ?>content.css"
                                                                                                                                                                                             		
                });
            });
        </script>



        <?php
        if ($this->hasModel()) {
            $textarea = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        } else {
            $textarea = CHtml::textArea($name, $this->value, $this->htmlOptions);
        }

        echo $textarea;
    }

}
