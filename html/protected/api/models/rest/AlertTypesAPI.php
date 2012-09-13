<?php

class AlertTypesAPI implements IAPI {

    public function getList($arguments = array()) {

        return AlertType::model()->findAll();
    }

    public function getSingle($id) {
        return AlertType::model()->findAllByPk($id);
    }

}

?>
