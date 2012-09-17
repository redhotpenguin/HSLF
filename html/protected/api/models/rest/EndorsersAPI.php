<?php

class EndorsersAPI extends APIBase implements IAPI {

    public function getList($arguments = array()) {
        return Endorser::model()->findAll();
    }

    public function getSingle($endorser_id) {
        return Endorser::model()->findByPk($endorser_id);
    }

    public function getPartialList() {
        return $this->getList();
    }

}

?>
