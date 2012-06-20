<?php

class SearchController extends Controller {

    private $_indexFiles = 'runtime.search';

    public function init() {
        Yii::import('application.vendors.*');
        require_once('Zend/Search/Lucene.php');
        parent::init();
    }

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
     */ public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    // move this to the admin section
    public function actionCreate() {
        $index = Zend_Search_Lucene::create(Yii::getPathOfAlias('application.' . $this->_indexFiles), true);

        $ballot_items = BallotItem::model()->findAll();
        foreach ($ballot_items as $ballot) {
            $doc = new Zend_Search_Lucene_Document();

            $year = date("Y", strtotime($ballot->date_published));
            $permalink = '/ballot/'.date('Y').'/'.$ballot->url;

            $doc->addField(Zend_Search_Lucene_Field::Keyword('ballot_id', $ballot->id));
            $doc->addField(Zend_Search_Lucene_Field::Text('title', CHtml::encode($ballot->item), 'utf-8'));
            $doc->addField(Zend_Search_Lucene_Field::Keyword('link', CHtml::encode($permalink), 'utf-8'));
            $doc->addField(Zend_Search_Lucene_Field::Text('content', $this->sanitize($ballot->detail), 'utf-8'));

            $index->addDocument($doc);
        }
        $index->commit();
        echo $index->count() . " Documents indexed.\n";
    }

    public function actionUpdate() {
        $index = Zend_Search_Lucene::open(Yii::getPathOfAlias('application.' . $this->_indexFiles), true);

        $hits = $index->find('ballot_id:1514');
        foreach ($hits as $hit) {
            print_r($hit->id);
            $index->delete($hit->id);
        }
    }

    private function sanitize($input) {
        return htmlentities(strip_tags($input));
    }

}