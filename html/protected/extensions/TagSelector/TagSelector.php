<?php

class TagSelector extends CInputWidget {

    public $options = array();

    public function init() {
        // check that the model has the correct behavior
        $behaviors = $this->model->behaviors();
        if(!isset($behaviors['TagRelation']))
            throw new CException('Model "'.get_class($this->model).'" does not have a behavior called TagRelation');
        
        
        $this->publishAssets();
    }

    public function run() {
        $data = array(
            'tags' => $this->model->getTags()
        );
        
        $this->render('tag_selector', $data);
    }

    public static function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/tagSelector.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/tagSelector.css');
        } else {
            throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
        }
    }

}