<?php

class TagSelector extends CInputWidget {

    public $options = array();
    public $tag_types = array();
    public $model_tags = array();
    public $display_tag_creator = true;

    public function init() {
        $this->publishAssets();
    }

    protected static function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/tag_creator.js', CClientScript::POS_END);
        }
    }

    public function run() {

        if (!empty($this->tag_types)) {
            $allTags = Tag::model()->findAllByAttributes(array("type" => $this->tag_types));
        } else {
            $allTags = Tag::model()->findAll();
        }

        $data = array(
            'model' => $this->model,
            'modelName' => get_class($this->model),
            'modelTags' => $this->model_tags,
            'tagTypes' => $this->tag_types,
            'displayTagCreator' => $this->display_tag_creator,
            'helpText' => isset($this->options['help_text']) ? $this->options['help_text'] : null,
        );

        $this->render('tag_selector', $data);
    }

}