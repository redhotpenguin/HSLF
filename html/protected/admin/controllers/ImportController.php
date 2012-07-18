<?php

class ImportController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'upload'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Display the Import home page
     */
    public function actionIndex() {
        $this->render('index');
    }

    public function actionUpload() {
        
        $tmp_name = $_FILES['import']['tmp_name'];
        
        if (!is_uploaded_file($tmp_name))
            return false;

        // open the temporary file in read only
        $fHandle = fopen($tmp_name, 'r');

        if (!$fHandle)
            throw new CException("Could not open file: " . $tmp_name);

        // empty array  for the transactional queries
        $queries = array();

        // csv column header
        $keys = fgetcsv($fHandle);
      
        $i = 0; // index
        while (($data = fgetcsv($fHandle, 0, ",")) !== FALSE) {
            $array[] = array_combine($keys, $data);

            $item = $array[$i]['item'];
            $item_type = $array[$i]['item_type'];
            $election_date = $array[$i]['next_election_date'];
            $priority = $array[$i]['priority'];
            $detail = $array[$i]['detail'];
            $date_published = $array[$i]['date_published'];

            $published = $array[$i]['published'];
            $party = $array[$i]['party'];
            $image_url = $array[$i]['image_url'];
            $url = $array[$i]['url'];
            $personal_url = $array[$i]['personal_url'];
            $office_type = $array[$i]['office_type'];
            $score = $array[$i]['score'];

            $state_abbr = $array[$i]['state'];
            $district_type = $array[$i]['district_type'];
            $district = (string) $array[$i]['district'];
            
            $recommendation_type = $array[$i]['recommendation_type'];
            $recommendation_value= $array[$i]['recommendation_value'];
            
            $recommendation_id = "(SELECT id from recommendation WHERE type = '$recommendation_type' AND value = '$recommendation_value' )";
     
            $district_id = "( SELECT id from district WHERE state_abbr = '$state_abbr' AND number = '$district' AND type = '$district_type' )";

            $insert_ballot_query = "INSERT INTO ballot_item ( district_id, item, item_type, next_election_date , priority, detail, date_published, published, party,image_url,url, personal_url, office_type, score, recommendation_id)
             VALUES($district_id, '$item', '$item_type', '$election_date' , $priority, '$detail', '$date_published' , '$published',  '$party' , '$image_url' , '$url', '$personal_url', '$office_type', '$score', $recommendation_id );";

            
            echo $insert_ballot_query;
            
            echo '<br>';
            
            array_push($queries, $insert_ballot_query);
            ++$i; // pre increment index
        }

        unset($array);

        fclose($fHandle);

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
  
   
        try {
            // create command for all the import queries
            foreach ($queries as $query)
                $connection->createCommand($query)->execute();

            // commit the transaction
            $transaction->commit();
            echo 'Upload complete.';
        } catch (Exception $e) {
            echo $e->getMessage();
            $transaction->rollBack();
        }
    }

}
