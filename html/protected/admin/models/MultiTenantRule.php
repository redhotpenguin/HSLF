<?php

class MultiTenantRule extends CBaseUrlRule {

    public $connectionID = 'db';
    
    private $tenant;

    public function createUrl($manager, $route, $params, $ampersand) {
        return "admin/{$this->tenant}/" . $route;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        error_log("Parse url:  $pathInfo and $rawPathInfo");
        
        $pieces = explode("/", $pathInfo);
        
       if(isset($pieces[1])){
           $this->tenant = $pieces[1];
       }else{
           return "site/login";
       }
                
        
            // check $matches[1] and $matches[3] to see
            // if they match a manufacturer and a model in the database
            // If so, set $_GET['manufacturer'] and/or $_GET['model']
            // and return 'car/index'
            
  

        return false;
    }

}

?>
