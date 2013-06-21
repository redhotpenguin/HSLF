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

    public function actionFindTag($term, $type = null) {
        header('Content-type: ' . 'application/json');

        if (strlen($term) < 2) {
            return;
        }

        $res = array();

        $tenantId = Yii::app()->user->getLoggedInUserTenant()->id;

        if ($term) {
            $query = 'SELECT id, name, display_name FROM tag where display_name ILIKE :display_name AND tenant_id =:tenant_id';
            
            if ($type) {
                $query .= ' AND type =:type';
            } 


            $cmd = Yii::app()->db->createCommand($query);
            $cmd->bindValue(":display_name", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);


            if ($type) {
                $cmd->bindValue(":type", $type, PDO::PARAM_STR);
            }

            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

}
