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

    private static function insertDataFromCSV($tmp_name, $file_name, $table_name, array $fields) {
        $fHandle = fopen($tmp_name, 'r');

        if (!$fHandle) {
            return "Could not open file: " . $file_name;
        }

        if (!file_get_contents($tmp_name)) {
            return "File is empty: " . $file_name;
        }

        // csv column header
        $keys = fgetcsv($fHandle);

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $transaction = $connection->beginTransaction();

        $i = 0; // index
        try {
            while (($data = fgetcsv($fHandle, 0, ",")) !== FALSE) {
                $mapped_data[] = array_combine($keys, $data);

                // insert statements
                if ($mapped_data[$i]['id'] == null) {
                    array_shift($mapped_data[$i]);
                    $command->insert($table_name, $mapped_data[$i]);
                }
                // update statements
                else {
                    $id = $mapped_data[$i]['id'];
                    array_shift($mapped_data[$i]);
                    $command->update($table_name, $mapped_data[$i], 'id=:id', array(':id' => $id)
                    );
                }
                ++$i;
            }
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


        $fields = array('state_abbr', 'number', 'type', 'display_name', 'locality');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'district', $fields);

        return $result;
    }

    public static function importBallot($tmp_name, $file_name) {

        $fields = array('district_id', 'item', 'item_type', 'recommendation_id', 'next_election_date', 'detail', 'date_published', 'published', 'party_id', 'image_url', 'election_result_id', 'url', 'personal_url', 'score', 'office_id', 'facebook_url', 'twitter_handle', 'hold_office', 'facebook_share', 'twitter_share', 'measure_number', 'friendly_name', 'keywords');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'ballot_item', $fields);

        return $result;
    }

    public static function importScorecard($tmp_name, $file_name) {

        $fields = array('ballot_item_id', 'scorecard_item_id', 'vote_id');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'scorecard', $fields);

        return $result;
    }

    public static function importeVote($tmp_name, $file_name) {

        $fields = array('name', 'icon');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'vote', $fields);

        return $result;
    }

    public static function importRecommendation($tmp_name, $file_name) {

        $fields = array('value', 'type');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'recommendation', $fields);

        return $result;
    }

    public static function importOffice($tmp_name, $file_name) {

        $fields = array('name');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'office', $fields);

        return $result;
    }

    public static function importParty($tmp_name, $file_name) {

        $fields = array('name', 'abbr', 'initial');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'party', $fields);

        return $result;
    }

    public static function importScorecardItem($tmp_name, $file_name) {

        $fields = array('name', 'description', 'office_id');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'scorecard_item', $fields);

        return $result;
    }

    public static function importTag($tmp_name, $file_name) {

        $fields = array('name', 'type');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'tag', $fields);

        return $result;
    }

    public static function importEndorser($tmp_name, $file_name) {

        $fields = array('name', 'description', 'website', 'image_url', 'display_name', 'list_name', 'slug');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'endorser', $fields);

        return $result;
    }

    public static function importEndorserBallotItem($tmp_name, $file_name) {

        $fields = array('endorser_id', 'ballot_item_id', 'position');

        $result = self::insertDataFromCSV($tmp_name, $file_name, 'endorser_ballot_item', $fields);

        return $result;
    }

}