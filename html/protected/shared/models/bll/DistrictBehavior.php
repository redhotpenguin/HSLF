<?php

class DistrictBehavior extends CActiveRecordBehavior {

    /**
     * Get all the district ids that match a specified state,specified types and speficied district numbers
     * @param string  $state_abbr  abbreviaton of the state
     * @param array  $district_types  district types
     * @param array $districts  district names
     * @return array array of district ids
     */
    public function getIdsByDistricts($state_abbr, array $district_types, array $districts, array $localities) {



        $state_id = State::model()->findByAttributes(array("abbr" => $state_abbr))->id;
        if ($state_id == null) {
            return array();
        }

        $command = Yii::app()->db->createCommand();
        // verify that $district_types and $districts have the same number of elements
        if (count($district_types) != count($districts))
            return false;

        /*
         * ex:
         * $condistion_string = 'state_abbr=:state_abbr  AND type=:district_type0 AND number=:district0 OR type=:district_type1 AND number=:district1';
         * 
          $condition_values = array(
          ':state_abbr' => $state_abbr,
          ':district_type0' => 'city',
          ':district0' => 'portland',
          ':district_type1' => 'county',
          ':district1' => 'clackamas'
          );
         * 
         */
        // construct the parameters needed for the query (see example above)
        $condition_string = 'state_id=:state_id ';
        $condition_values = array(':state_id' => $state_id);

        foreach ($district_types as $i => $district_type) {
            if ($i > 0) {
                $condition_string .= ' OR state_id=:state_id AND type=:district_type' . $i . ' AND number=:district' . $i;
            } else {
                $condition_string .= ' AND state_id=:state_id AND type=:district_type' . $i . ' AND number=:district' . $i;
            }

            // needed because some district types don't have a district number. Ex: statewide
            if (empty($districts[$i]))
                $districts[$i] = '';

            $condition_values[":district_type{$i}"] = $district_type;
            $condition_values[":district{$i}"] = $districts[$i];

            if (isset($localities[$i])) {
                $condition_string.= " AND locality=:locality{$i}";

                $condition_values[":locality{$i}"] = $localities[$i];
            }
        }
        
        // execute the command
        $result = $command->select('id')
                ->from('district')
                //- WHERE state_abbr 
                ->where($condition_string, $condition_values)
                ->queryAll();


        if ($result)
            return array_map(array($this, 'extract_id'), $result);    // return flat array

        else
            return false;
    }

    private function extract_id($a) {
        return $a['id'];
    }

}