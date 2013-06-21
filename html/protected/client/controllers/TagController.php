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

    
    /**
     * Print JSON tags and end the application
     * @param string $term  - tag display name
     * @param array $types - tag types (optional)
     */
    public function actionFindTag($term, array $types = null) {
        header('Content-type: ' . 'application/json');

        if (strlen($term) < 2) {
            return;
        }

        $res = array();

        $tenantId = Yii::app()->user->getLoggedInUserTenant()->id;

        if ($term) {
            $query = 'SELECT id, name, display_name FROM tag where display_name ILIKE :display_name AND tenant_id =:tenant_id';

            if ($types) {


                $typeCondition = "";
                foreach ($types as $i => $type) {
                    if ($i == 0) {
                        $typeCondition .= " type =:type$i ";
                    } else {
                        $typeCondition .= "OR type =:type$i ";
                    }
                }

                $query .= " AND ( $typeCondition )";
            }



            $cmd = Yii::app()->db->createCommand($query);
            $cmd->bindValue(":display_name", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);



            if ($types) {
                foreach ($types as $i => $type) {
                    $cmd->bindValue(":type{$i}", $type, PDO::PARAM_STR);
                }
            }

            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

}
