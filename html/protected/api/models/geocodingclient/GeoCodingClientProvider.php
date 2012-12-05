<?php

//  factory
class GeoCodingClientProvider {

    private $tenantId;

    public function __construct($tenantId) {
        $this->tenantId = $tenantId;
    }

    public function getGeoCodingClient($type) {
        if ($type == 'cicero') {
            $httpClient = new CurlHttpRequestClient();


            $tenant = Tenant::model()->findByPk($this->tenantId);

            $options = array(
                'tenantId' => $this->tenantId,
                'username' => $tenant->cicero_user,
                'password' => $tenant->cicero_password
            );


            return new CiceroGeoCodingClient($httpClient, $options);
        } else {
            throw new Exception('Not implemented');
        }
    }

}
