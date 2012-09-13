<?php

class TagsAPI extends APIBase  implements IAPI {

    public function getList($arguments = array()) {

        return Tag::model()->findAll();
    }

    public function getSingle($id) {
        return Tag::model()->findAllByPk($id);
    }

}

?>
