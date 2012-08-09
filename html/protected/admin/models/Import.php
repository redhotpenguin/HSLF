<?php

class Import extends CModel {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PushNotification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeNames() {
        
    }

    private static function insertDataFromCSV($tmp_name, $file_name, $table_name, $fields) {
        $fHandle = fopen($tmp_name, 'r');
        $queries = array();
        $data_field = array();

        if (!$fHandle) {
            return "Could not open file: " . $file_name;
        }

        if (!file_get_contents($tmp_name)) {
            return "File is empty: " . $file_name;
        }

        // csv column header
        $keys = fgetcsv($fHandle);



        $i = 0; // index
        while (($data = fgetcsv($fHandle, 0, ",")) !== FALSE) {
            $data_field[] = array_combine($keys, $data);

            if ($data_field[$i]['id'] != null) { // update 
                $id = $data_field[$i]['id'];

                array_shift($data_field[$i]); // remove the first element of the array (id ) 
                $set = "";
                $j = 0;
                $data_field_size = count($data_field[$i]) - 1;

                foreach ($data_field[$i] as $column => $value) {
                    $set.=" $column = '$value' ";
                    if ($j < $data_field_size)
                        $set.= ',';

                    $j++;
                }
                                
                $query = "UPDATE  $table_name SET  $set  WHERE id = $id ;";
            } else { // insert
                array_shift($data_field[$i]);
                $columns = implode(',', $fields);
                $values = implode("','", $data_field[$i]);
                $query = "INSERT INTO $table_name ($columns) VALUES('$values');";
               
               echo '<br>';
            }



            array_push($queries, $query);
            ++$i; // pre increment index
        }
        
        // print_r($queries);
       // exit;

        unset($data_field);

        fclose($fHandle);

        if (empty($queries))
            return "Wrong data format";


        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {
            // create command for all the import queries
            foreach ($queries as $query)
                $connection->createCommand($query)->execute();

            // commit the transaction
            $transaction->commit();
            $result = true;
        } catch (Exception $e) {

            $result = $e->getMessage();
            $transaction->rollBack();
        }
        return $result;
    }

    public static function importState($tmp_name, $file_name) {
        $fields = array('abbr', 'name');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'state', $fields);

        return $result;
    }

    public static function importDistrict($tmp_name, $file_name) {

        $fields = array('state_abbr', 'number', 'type', 'display_name');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'district', $fields);

        return $result;
    }

    public static function importBallot($tmp_name, $file_name) {

        $fields = array('district_id', 'item', 'item_type', 'recommendation_id', 'next_election_date', 'priority', 'detail', 'date_published', 'published', 'party', 'image_url','election_result_id','url', 'personal_url', 'score', 'office_id');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'ballot_item', $fields);

        return $result;
    }

    public static function importScorecard($tmp_name, $file_name) {

        $fields = array( 'ballot_item_id', 'scorecard_item_id', 'vote_id');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'scorecard', $fields);

        return $result;
    }

}