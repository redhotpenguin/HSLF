<?php

Yii::import('system.web.CUrlManager', true);

class TenantUrlRule extends CBaseUrlRule {

    private $currentTenant;

    public function __construct() {
        $this->currentTenant = Yii::app()->user->getCurrentTenant();
    }

    public function createUrl($manager, $route, $params, $ampersand) {
        if ($this->currentTenant) {
            $tenantName = $this->currentTenant->name;
            $route = 'admin/' . $tenantName . '/' . $route;
            // rebuild query string, there should be a better way to do this
            if (!empty($params)) {
                $route.='?';
                $i = 0;
                $paramsCount = count($params) - 1;
                foreach ($params as $k => $v) {
                    $route.="{$k}={$v}";

                    if ($i < $paramsCount)
                        $route.=$ampersand;
                    $i++;
                }
            }

            return $route;
        }
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        
    }

}

