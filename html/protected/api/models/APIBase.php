<?php

abstract class APIBase implements IAPI {

    const AUTH_REQUIRED = 'Authentication required';

    protected $model;
    protected $isAuthenticated;
    protected $requiresAuth;

    public function __construct(CActiveRecord $model, $requiresAuth = false) {
        $this->model = $model;
        $this->requiresAuth = $requiresAuth;
    }

    public final function setAuthenticated($authenticated) {
        $this->isAuthenticated = $authenticated;
    }

    public function getList($arguments = array()) {

        // doesn't require auth or is authenticated
        if (!$this->requiresAuth || $this->isAuthenticated) {
          
            // filter by a single attribute
            if (isset($arguments['attributeValue']) && isset($arguments['attribute']) && $this->model->hasAttribute($arguments['attribute'])) {

                try {
                    $result = $this->model->findAllByAttributes(array($arguments['attribute'] => $arguments['attributeValue']));
                } catch (CDbException $cdbException) {
                    $result = "error";
                }
                return $result;
            }
            // no attributes specified, return all the rows
            else
                return $this->model->findAll();
        }


        else
            return self::AUTH_REQUIRED;
    }

    public function getSingle($pkID) {

        // doesn't require auth or is authenticated
        if (!$this->requiresAuth || $this->isAuthenticated)
            return $this->model->findByPk($pkID);

        else
            return self::AUTH_REQUIRED;
    }

}

?>
