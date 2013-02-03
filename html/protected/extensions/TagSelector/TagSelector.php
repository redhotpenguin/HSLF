<?php

class TagSelector extends CInputWidget {

    public $options = array();
    public $tag_type;

    public function init() {
        // check that the model has the correct behavior
        $behaviors = $this->model->behaviors();
        if (!isset($behaviors['TagRelation']))
            throw new CException('Model "' . get_class($this->model) . '" does not have a behavior called TagRelation');
    }

    public function run() {

        $allTags = Tag::model()->findAllByAttributes(array("type" => $this->tag_type));

        $associatedTags = $this->model->getTags();

        $unAssociatedTags = array_diff($allTags, $associatedTags);
        
        $data = array(
            'associatedTags' => $associatedTags,
            'unAssociatedTags' => $unAssociatedTags,
            'modelName' => get_class($this->model),
        );

        $this->render('tag_selector', $data);
    }

}