<?php

class StatesAPI extends APIBase  implements IAPI {

    public function getList( $arguments = array() ) {
        return State::model()->findAll();
    }

    public function getSingle($state_abbr) {
        return State::model()->findByPk($state_abbr);
    }

}
