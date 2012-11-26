<?php

//  abstract factory
class GeoCodingClientProvider {

    function getGeoCodingClient($type) {
        if ($type == 'cicero') {
            $httpClient = new CurlHttpRequestClient();
            return new CiceroGeoCodingClient($httpClient);
        } else {
            throw new Exception('Not implemented');
        }
    }

}

?>
