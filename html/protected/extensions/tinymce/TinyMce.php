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
        ?>


        <script type="text/javascript">
            $().ready(function() {
                $('#<?php echo $id?>').tinymce({
                    // Location of TinyMCE script
                    script_url : '<?php echo $baseUrl . '/jscripts/tiny_mce/'; ?>tiny_mce.js',
                    elements:'<?php echo $id; ?>',
                    mode:'exact',
                    // General options
                    theme : "advanced",
<<<<<<< HEAD
                    plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist,imagemanager",
=======
                    plugins : "imagemanager,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",
>>>>>>> 6780a824bab8b7e058cfbf1d5065b62440cc8c4a
                    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                    theme_advanced_buttons2 : "insertimage,cut,copy,paste,pastetext,pasteword,|,search,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                    theme_advanced_buttons4 : "moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking",
                    theme_advanced_toolbar_location : "top",
                    theme_advanced_toolbar_align : "left",
                    theme_advanced_resizing : true,
                    theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px, 22px",


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
