<?php

class TagSelector extends CInputWidget {

    public $options = array();
    public $tag_types = array();

    public function init() {
        // check that the model has the correct behavior
        $behaviors = $this->model->behaviors();
        if (!isset($behaviors['TagRelation']))
            throw new CException('Model "' . get_class($this->model) . '" does not have a behavior called TagRelation');

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
            'modelTags' => $this->model->getTags(),
            'tagTypes' => $this->tag_types
        );

        $this->render('tag_selector', $data);
    }
}