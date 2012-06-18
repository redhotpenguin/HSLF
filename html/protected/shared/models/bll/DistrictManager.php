<?php

/**
 *  Business Logics for the District model
 *
 * @author jonas
 */
class DistrictManager {

    /**
     * Get all the district ids within a state
     * @param integer $state_abbr  abbreviaiton of the state
     * @return array array of district ids
     */
    public static function getIdsByState($state_abbr) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr', array(':state_abbr' => $state_abbr))
                ->queryAll();

        return array_map(array(self , 'extract_id'), $result);
    }

    /**
     * Get all the district ids that match a specified state,specified types and speficied district numbers
     * @param string  $state_abbr  abbreviaton of the state
     * @param array  $district_types  district types
     * @param array $districts  district names
     * @return array array of district ids
     */
    public static function getIdsByDistricts($state_abbr, array $district_types, array $districts) {
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
        $condition_string = 'state_abbr=:state_abbr ';
        $condition_values = array(':state_abbr' => $state_abbr);

        foreach ($district_types as $i => $district_type) {
            if ($i > 0)
                $condition_string .= ' OR state_abbr=:state_abbr AND type=:district_type' . $i . ' AND number=:district' . $i;
            else
                $condition_string .= ' AND state_abbr=:state_abbr AND type=:district_type' . $i . ' AND number=:district' . $i;

            // needed because some district types don't have a district number. Ex: statewide
            if (empty($districts[$i]))
                $districts[$i] = '';

            $condition_values[":district_type{$i}"] = $district_type;
            $condition_values[":district{$i}"] = $districts[$i];
        }

        // execute the command
        $result = $command->select('id')
                ->from('district')
                //- WHERE state_abbr 
                ->where($condition_string, $condition_values)
                ->queryAll();


        if ($result)
        // return flat array
            return array_map(array(self, 'extract_id'), $result);

        else
            return false;
    }

    private static function extract_id($a) {
        return $a['id'];
    }
    
     /**
     * Retrieve the District ID based on $state and $district_number
     * DEPRECATED
     */
    public static function getIdByStateAndDistrict($state, $district_number) {
        $district = District::model()->findByAttributes(array('state_abbr' => $state, 'number' => $district_number));
        if ($district)
            return $district->id;
        else
            return false;
    }

    /**
     * Retrieve the District ID based on state, type and district name
     * @param string $state_abbr  abbreviaton of the state
     * @param string $type  district type
     * @param string $district  district name
     * return district id
     */
    public static function getDistrictId($state_abbr, $type, $district) {
        $district = District::model()->findByAttributes(array('state_abbr' => $state_abbr, 'type' => $type, 'number' => $district));
        if ($district)
            return $district->id;
        else
            return false;
    }


    /**
     * Get all the district ids within a state and of a specified type
     * @param integer $state_abbr  abbreviaiton of the state
     * @param integer $district  district type
     * @return array array of district ids
     */
    public static function getIdsByDistrictType($state_abbr, $district_type) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr AND type=:district_type', array(':state_abbr' => $state_abbr, ':district_type' => $district_type))
                ->queryAll();

        return array_map(array(self, 'extract_id'), $result);
    }



    /**
     * Get all the district ids that match a specified state, a specified type and a speficied district number
     * @param string $state_abbr  abbreviaton of the state
     * @param string $type  district type
     * @param string $district  district name
     * @return array array of district ids
     */
    public static function getIdsByDistrict($state_abbr, $district_type, $district) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr AND type=:district_type AND number=:district_number', array(':state_abbr' => $state_abbr, ':district_type' => $district_type, ':district_number' => $district))
                ->queryAll();

        return array_map(array(self, 'extract_id'), $result);
    }

   

    /**
     * Prepend the keyword 'type' to an element
     * @param type $a
     * @return type 
     */
    function addType($a) {
        return 'type=' . "'$a'";
    }

}

?>
