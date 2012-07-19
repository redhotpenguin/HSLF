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
                'actions' => array('index', 'BallotItemUpload'),
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

        $tmp_name = $_FILES['import']['tmp_name'];

        if (!is_uploaded_file($tmp_name)) {
            $this->render('index');
            return;
        }

        $allowed_mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
        if (!in_array($_FILES['import']['type'], $allowed_mimes)) {
            $this->render('index', array('result' => 'File format not allowed. Please use CSV.'));
            return;
        }

        switch (getParam('action')) {
            case 'importBallot':
                $import_result = Import::importBallot($_FILES['import']['tmp_name'], $_FILES['import']['name']);

                if ($import_result === true)
                    $result = 'success';
                else
                    $result = '<b>Something went wrong:</b><br/>'.$import_result;

                break;

            default:
                $result = 'Operation not supported';
                break;
        }

        $this->render('index', array('result' => $result));
        return;
    }

}
