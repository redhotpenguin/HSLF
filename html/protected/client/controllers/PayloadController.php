<?php

class PayloadController extends CrudController {

    public function __construct() {
        parent::__construct('payload');
        $this->setModelName('Payload');
        $this->setFriendlyModelName('Payload');
    }

    public function actionFindPayload($term) {
        $res = array();

        $tenantId = Yii::app()->user->getLoggedInUserTenant()->id;

        if ($term) {

            // ILIKE only works with postgresql
            if (substr(strtolower(Yii::app()->db->connectionString), 0, 5) === 'pgsql')
                $sql = 'SELECT id, title FROM payload where title ILIKE :title AND tenant_id =:tenant_id';
            else
                $sql = 'SELECT id, title FROM payload where title LIKE :title AND tenant_id =:tenant_id';

            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":title", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);
            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

}
