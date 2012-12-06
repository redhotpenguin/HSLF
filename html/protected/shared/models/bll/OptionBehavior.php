<?php

class OptionBehavior extends CActiveRecordBehavior {

    public function upsert($name, $value) {

        $option = $this->owner->findByAttributes(array("name" => $name));

        if ($option == null) { // insert
            $this->owner->name = $name;
            $this->owner->value = $value;
            $saveResult = $this->owner->save(false);

            return $saveResult;
        } else { // update
            $this->owner->isNewRecord = false;
            $this->owner->id = $option->id;
            $this->owner->name = $option->name;
            $this->owner->value = $value;
            $updateResult = $this->owner->save();
            return $updateResult;
        }
    }

}

?>
