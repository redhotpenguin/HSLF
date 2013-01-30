<?php

Yii::import('system.web.CUrlManager', true);

class TenantUrlRule extends CBaseUrlRule {

    private $currentTenant;

    public function __construct() {
        $this->currentTenant = Yii::app()->user->getCurrentTenant();
    }

    /**
@todo: optimize this function
     */
    public function createUrl($manager, $route, $params, $ampersand) {

        if ($this->currentTenant) {
            $tenantName = $this->currentTenant->name;

            $route = 'admin/' . $tenantName . '/' . $route;
            if (isset($params['id']))
                $route.= '?id='. $params['id'];

           //  logIt($route);

            return $route;
        }
        


        return $route;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        
    }

}

