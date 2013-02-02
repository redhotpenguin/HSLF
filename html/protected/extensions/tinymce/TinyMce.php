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
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jscripts/tiny_mce/jquery.tinymce.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
        }
    }

    public function run() {
        $assets = dirname(__FILE__) . '/assets';

        $baseUrl = Yii::app()->assetManager->publish($assets);

        list($name, $id) = $this->resolveNameID();

        // theme found at http://tinymce.swis.nl/demo
        ?>


        <script type="text/javascript">
            $().ready(function() {
                $('#<?php echo $id ?>').tinymce({
                    // Location of TinyMCE script
                    script_url : '<?php echo $baseUrl . '/jscripts/tiny_mce/'; ?>tiny_mce.js',
                    elements:'<?php echo $id; ?>',
                    mode:'textareas',
                    theme : "ribbon",
                    inlinepopups_skin : 'ribbon_popup',                                                                                                   
                    plugins : "tableextras,embed, tabfocus,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",                                                                                           
                    theme_advanced_statusbar_location  : "none",
                    theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px, 22px",
                    theme_advanced_toolbar_align : "left",                                             
                    relative_urls : false,
                    convert_urls : false,       
                    theme_ribbon_tab1 : {   title : "Start",
                        items : [
                            ["paste"], 
                            ["justifyleft,justifycenter,justifyright,justifyfull",
                                "bullist,numlist",
                                "|",     
                                "bold,italic,underline",
                                "outdent,indent"], 
                            ["paragraph", "heading1", "heading2", "heading3"],
                            ["fontselect","|","fontsizeselect"],
                            ["search", "|", "replace", "|", "removeformat"]]
                    },
                                                                                                                            

                                                                                                                            
                    theme_ribbon_tab2 : {   title : "Insert",
                        items : [["tabledraw"],
                            ["embed"],
                            ["link", "|", "unlink", "|", "anchor"],
                            ["loremipsum", "|", "charmap", "|", "hr"]]
                    },
                                                                                                        
                    theme_ribbon_tab3 : {   title : "Source",
                        source : true                                                                                       
                    },                                                                                                                                           
                    content_css : "<?php echo $baseUrl . '/css/'; ?>content.css"
                                                                                                                                                                     		
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
