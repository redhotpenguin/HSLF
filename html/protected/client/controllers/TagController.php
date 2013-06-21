<?php

class TagController extends CrudController {

    public function __construct() {
        parent::__construct('tag');
        $this->setModel(new Tag);
        $this->setFriendlyModelName('Tag');


        $rules = array(
            array('allow',
                'actions' => array('findTag'),
                'roles' => array('readTag'),
            )
        );

        $this->setExtraRules($rules);
    }

    public function actionFindTag($term) {   
        if(strlen($term) < 3){
            return;
        }
        
        $res = array();

        $tenantId = Yii::app()->user->getLoggedInUserTenant()->id;

        if ($term) {

            // ILIKE only works with postgresql
            if (substr(strtolower(Yii::app()->db->connectionString), 0, 5) === 'pgsql')
                $query = 'SELECT id, name, display_name FROM tag where display_name ILIKE :display_name AND tenant_id =:tenant_id';
            else
                $query = 'SELECT id, name, display_name FROM tag where display_name LIKE :display_name AND tenant_id =:tenant_id';

            $cmd = Yii::app()->db->createCommand($query);
            $cmd->bindValue(":display_name", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);
            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

}
