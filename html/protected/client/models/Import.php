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
        $header = fgetcsv($fHandle);
        $headerCount = count($header);
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $transaction = $connection->beginTransaction();

        $i = 0; // index

        try {
            while (($data = fgetcsv($fHandle, 0, ",")) !== FALSE) {

                if ($headerCount != count($data)) {
                    throw new Exception('Data format issue');
                }

                // array_combine excepts $header and $data to have the same number of items
                $mapped_data[] = array_combine($header, $data);

                $id = $mapped_data[$i]['id'];
                array_shift($mapped_data[$i]);  // remove the ID column 

                if ($id == null) {  // No ID, insert row    
                    $command->insert($table_name, $mapped_data[$i]);
                } else { // ID found, update row
                    $command->update($table_name, $mapped_data[$i], 'id=:id', array(':id' => $id));
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

    public static function importModel($modelName, $tmpName, $fileName) {
        $model = new $modelName();

        $tableName = $model->tableName();

        $attributes = $model->getAttributes();

        $result = self::insertDataFromCSV($tmpName, $fileName, $tableName, $attributes);

        return $result;
    }

}