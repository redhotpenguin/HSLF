<?php

/**
 * Description of FormPreview
 *
 * @author Jonas Palmero
 */
class FormPreview extends CWidget {

    public $options = array();

    //Initializes the widget.
    public function init() {
        $this->publishAssets();
    }

    protected static function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/preview.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/preview.css');
        } else {
            throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
        }
    }

    //Executes the widget. 
    public function run() {
        $fields_to_preview = $this->options['fields'];
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                var fields_to_preview = new Array("<?php echo implode('","', $fields_to_preview) ?>");
                     
                jQuery(fields_to_preview).each(function(index,field){
                    $('#'+field).add_preview();
                });
                      
            });
        </script>

        <?php
    }

}
?>
