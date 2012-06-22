<?php

/**
 * Behavior class for the Application user Model
 *
 * @author jonas
 */
class ApplicationUserMetaBehavior extends CActiveRecordBehavior{

    public function updateUserMeta(){
        error_log('test!!!');
        error_log($this->owner->device_token);
    }

}

?>
