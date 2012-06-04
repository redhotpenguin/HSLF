<?php

class BallotController extends Controller {

    public $layout = '//layouts/main';

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        echo 'index';
        print_r($_GET);

        $ballots = BallotItem::model()->findAll();

        $this->render('index', array('ballots' => $ballots));
    }


    public function actionView() {
        $year = $_GET['year'];
        $slug = $_GET['slug'];
        
        echo 'view';
        print_r($_GET);
       

        $ballot = BallotItem::model()->findByPublishedYearAndSlug($year, $slug);

        $this->render('view', array(
            'ballot' => $ballot,
        ));
    }

    public function actionList() {
        echo 'list';
        print_r($_GET);
        echo 'list';
    }

    public function actionListByDistrict() { // /ballot/ca/congressional/12
        // todo: sanitize and validate get parameters
        $this->setPageTitle('custom page title');
        
        $state_abbr = $_GET['state_abbr'];
        $district_type = $_GET['district_type'];
        $district = $_GET['district'];


        $ballots = BallotItem::model()->findAllByDistrict($state_abbr, $district_type, $district);
        
        if(empty($ballots)){
            throw new CHttpException(404,'The specified post cannot be found.');
        }
       
        echo 'list by district';
        
        $this->render('list', array(
            'state_abbr' => $state_abbr,
            'district_type' => $district_type,
            'district' => $district,
            'ballots' => $ballots)
        );
    }

}