<?php

/**
 * External Behaviors for the Ballot Item class
 *
 * @author jonas
 */
class BallotItemBehavior extends CActiveRecordBehavior {

    public function beforeSave() {

        if (!$this->owner->url) {

            $new_url = str_replace(' ', '-', $this->owner->item);
            $new_url = strtolower($new_url);

            $this->owner->url = $new_url;
            
            $this->owner->save();
        }
    }

}

?>
