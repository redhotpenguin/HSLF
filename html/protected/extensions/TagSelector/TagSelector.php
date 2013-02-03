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

        $checkBoxList = array();
        
        // uses array indexes to store the tag id
        foreach ($associatedTags as $tag)
            $checkBoxList[$tag->id] = array('name' => $tag->display_name, 'checked' => true);

        foreach ($unAssociatedTags as $tag)
            $checkBoxList[$tag->id] = array('name' => $tag->display_name, 'checked' => false);

        // sort array and maintain indexes
        asort($checkBoxList);

        $data = array(
            'modelName' => get_class($this->model),
            'checkBoxList' => $checkBoxList,
        );

        $this->render('tag_selector', $data);
    }

}