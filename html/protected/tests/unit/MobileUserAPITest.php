<?php

/**
 * Unitary tests for MobileUser
 *
 * @author Jonas
 */
class MobileUserAPITest extends CDbTestCase {

    public function testCreateUser() {
        $response = "failure";


        define('PUSHURL', 'http://localhost/test/receive_json.php');

        $contents = array();
        $contents['badge'] = "+1";
        $contents['alert'] = "Howdy, doody";
        $contents['sound'] = "cow";
        $push = array("aps" => $contents);

        $json = json_encode($push);

        $test = array(
            'user'=>$json
        );
        
        $session = curl_init(PUSHURL);
        //curl_setopt($session, CURLOPT_USERPWD, APPKEY . ':' . PUSHSECRET); 
        curl_setopt($session, CURLOPT_POST, True);
        curl_setopt($session, CURLOPT_POSTFIELDS, $test);
        curl_setopt($session, CURLOPT_HEADER, False);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
        $content = curl_exec($session);
        echo $content; // just for testing what was sent
        // Check if any error occured 
        $response = curl_getinfo($session);
        if ($response['http_code'] != 200) {
            echo "Got negative response from server, http code: " .
            $response['http_code'] . "\n";
        } else {

            echo "Wow, it worked!\n";
        }

        curl_close($session);

        $this->assertEquals("success", $response);
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

}

?>
