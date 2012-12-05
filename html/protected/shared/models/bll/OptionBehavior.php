<?php

class OptionBehavior extends CActiveRecordBehavior {

    public function upsert($name, $value) {

        $option = $this->owner->findByAttributes(array("name" => $name));

        if ($option == null) { // insert
            $this->owner->name = $name;
            $this->owner->value = $value;
            return $this->owner->save();
        } else { // update
            $option->value = $value;
            return $option->save();
        }
    }

}

?>
