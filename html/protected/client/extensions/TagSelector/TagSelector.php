<?php

class TagSelector extends CInputWidget {

    public $options = array();
    public $tag_types = array();

    public function init() {
        // check that the model has the correct behavior
        $behaviors = $this->model->behaviors();
        if (!isset($behaviors['TagRelation']))
            throw new CException('Model "' . get_class($this->model) . '" does not have a behavior called TagRelation');
    }

    public function run() {

        $allTags = Tag::model()->findAllByAttributes(array("type" => $this->tag_types));

        $associatedTags = $this->model->getTags();

        $unAssociatedTags = array_diff($allTags, $associatedTags);

        $checkBoxList = array();

        // uses array indexes to store the tag id
        foreach ($associatedTags as $tag)
            $checkBoxList[$tag->id] = array('name' => $tag->display_name, 'checked' => true);

        foreach ($unAssociatedTags as $tag)
            $checkBoxList[$tag->id] = array('name' => $tag->display_name, 'checked' => false);
        
        $checkBoxList = $this->array_sort($checkBoxList, 'name', SORT_ASC );

        $data = array(
            'modelName' => get_class($this->model),
            'checkBoxList' => $checkBoxList,
        );

        $this->render('tag_selector', $data);
    }

    private function array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    natcasesort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

}