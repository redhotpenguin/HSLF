<?php

class ImportController extends Controller {

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
            array(// restrict State to admins only
                'allow',
                'actions' => array('index'),
                'roles' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Display the Import home page
     */
    public function actionIndex($modelName = "") {
        $result = "";
        $errorMsg = "";
        $allowed_mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
        $models = array();

        // allowed models for imports
        $allowedModelNames = array(
            'Contact' => 'Contacts',
            "District" => "Districts",
            "BallotItem" => "Items",
            "Office" => "Offices",
            "Option" => "Options",
            "Organization" => "Organizations",
            "Party" => "Parties",
            "Recommendation" => "Recommendations",
            "State" => "States",
            "Tag" => "Tags",
            "Vote" => "Votes",
        );

        //
        // if a file has been submitted
        if (( isset($_FILES['import']) && in_array($_FILES['import']['type'], $allowed_mimes))) {

            // if the file has been successfully uploaded
            if (isset($_FILES['import']['tmp_name']) || is_uploaded_file($_FILES['import']['tmp_name'])) {

                // restrict model import to the one defined in $allowedModelNames
                if (array_key_exists($modelName, $allowedModelNames)) {

                    $importResult = Import::importModel($modelName, $_FILES['import']['tmp_name'], $_FILES['import']['tmp_name']);

                    if ($importResult === true)
                        $result = 'success';
                    else {
                        $result = 'failure';
                        $errorMsg = '<b>Something went wrong:</b><br/>' . $importResult;
                    }
                }
            }
        }

        foreach ($allowedModelNames as $allowedModelName => $friendlyName) {
            $model = new $allowedModelName();

            array_push($models, array(
                'name' => $allowedModelName,
                'friendlyName' => $friendlyName,
                'attributes' => $model->getAttributes()
            ));
        }

        $this->render('index', array(
            'result' => $result,
            'errorMsg' => $errorMsg,
            'models' => $models
        ));
    }

}
