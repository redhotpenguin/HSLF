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

    public static function importBallot($tmp_name, $file_name) {
        $result = null;
        $data_field = array();
        // open the temporary file in read only
        $fHandle = fopen($tmp_name, 'r');

        if (!$fHandle) {
            return "Could not open file: " . $file_name;
        }

        if (!file_get_contents($tmp_name)) {
            return "File is empty: " . $file_name;
        }

        // empty array  for the transactional queries
        $queries = array();

        // csv column header
        $keys = fgetcsv($fHandle);


        $i = 0; // index
        while (($data = fgetcsv($fHandle, 0, ",")) !== FALSE) {
            $data_field[] = array_combine($keys, $data);

            $item = $data_field[$i]['item'];
            $item_type = $data_field[$i]['item_type'];
            $election_date = $data_field[$i]['next_election_date'];
            $priority = $data_field[$i]['priority'];
            $detail = $data_field[$i]['detail'];
            $date_published = $data_field[$i]['date_published'];
            
            if (empty($date_published))
                $date_published = 'now()::timestamp(0)'; // used DB current time if the publication field is not given
            else
                $date_published = "'$date_published'";

            $published = $data_field[$i]['published'];
            $party = $data_field[$i]['party'];
            $image_url = $data_field[$i]['image_url'];
            $url = $data_field[$i]['url'];
            $personal_url = $data_field[$i]['personal_url'];
            $office_type = $data_field[$i]['office_type'];
            $score = $data_field[$i]['score'];

            $state_abbr = $data_field[$i]['state_abbr'];
            $district_type = $data_field[$i]['district_type'];
            $district = (string) $data_field[$i]['district'];

            $recommendation_type = $data_field[$i]['recommendation_type'];
            $recommendation_value = $data_field[$i]['recommendation_value'];

            $recommendation_id = "(SELECT id from recommendation WHERE type = '$recommendation_type' AND value = '$recommendation_value' )";

            $district_id = "( SELECT id from district WHERE state_abbr = '$state_abbr' AND number = '$district' AND type = '$district_type' )";

            $insert_ballot_query = "INSERT INTO ballot_item ( district_id, item, item_type, next_election_date , priority, detail, date_published, published, party,image_url,url, personal_url, office_type, score, recommendation_id)
             VALUES($district_id, '$item', '$item_type', '$election_date' , $priority, '$detail', $date_published , '$published',  '$party' , '$image_url' , '$url', '$personal_url', '$office_type', '$score', $recommendation_id );";

            array_push($queries, $insert_ballot_query);
            ++$i; // pre increment index
        }

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

}