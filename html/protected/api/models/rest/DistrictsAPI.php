<?php

class DistrictsAPI extends APIBase implements IAPI {

    public function getList($arguments = array()) {
        return District::model()->findAll();
    }

    public function getSingle($state_abbr) {
        return District::model()->findByPk($state_abbr);
    }

    public function getPartialList() {
        return $this->getList();
    }

}
