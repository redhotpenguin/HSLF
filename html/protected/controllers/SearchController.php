<?php

class SearchController extends Controller {

    public $layout = '//layouts/main';

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        $this->render('index');
    }

    private $_indexFiles = 'runtime.search';

    public function init() {
        Yii::import('application.vendors.*');
        require_once('Zend/Search/Lucene.php');
        parent::init();
    }

  
    public function actionSearch() {
        if (($term = Yii::app()->getRequest()->getParam('q', null)) !== '') {
            try{
            $index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles));
            }
            catch(Exception $e){
                error_log("Front end Search controller: ". $e->getMessage() );
                return false;
            }
            
            $results = $index->find($term);
            $query = Zend_Search_Lucene_Search_QueryParser::parse($term);
            $this->render('search', compact('results', 'term', 'query'));
        }  else {
            $this->render('index');
        }
    }
}