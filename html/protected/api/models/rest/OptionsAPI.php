<?php

class OptionsAPI extends APIBase implements IAPI {

    public function getList($arguments = array()) {

        if ($this->isAuthenticated)
            $result = Option::model()->findAll();
        else
            $result = self::AUTH_REQUIRED;


        return $result;
    }

    public function getSingle($id) {


        if ($this->isAuthenticated)
            $result = Option::model()->findByPk($id);
        else
            $result = self::AUTH_REQUIRED;


        return $result;
    }

}

