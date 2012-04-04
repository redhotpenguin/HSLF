<?php

/**
 * Description of FormPreview
 *
 * @author Jonas Palmero
 */
class FormPreview extends CWidget {

    public $options = array();
    public $fields;
    public $form_id;
    public $filters;

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
        } else {
            throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
        }
    }

    //Executes the widget. 
    public function run() {
        $fields_to_preview = $this->fields;
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
             
                var fields_to_preview = {
                    <?php 
                       $n_fields = count($fields_to_preview['fields']);
                       
                        foreach($fields_to_preview['fields'] as $k=>$field){
                            echo $field[0].':';
                            if(isset($field[1]))
                                 echo $field[1];
                            else
                                echo 'null';
                            
                            if($k < $n_fields -1)
                                 echo ',';
                        }
                    ?>
                } // fields_to_preview end
              
                for(var field in fields_to_preview) {
                    var filter = fields_to_preview[field];
                    $('#'+field).add_preview(filter);
                }
            });
        </script>

        <?php

    }

}
?>