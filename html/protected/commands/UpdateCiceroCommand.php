<?php

class UpdateCiceroCommand extends CConsoleCommand {

    public function getHelp() {
        return <<<EOD

DESCRIPTION:
Update the Cicero token for a tenant

USAGE:
updatecicero <tenantID>

EX:
updatecicero 12   

EOD;
    }

    public function run($args) {
        $tenantId = $args[0];

        if (!isset($tenantId)) {
            exit("A tenant ID must be specified.");
        }

        $tenant = Tenant::model()->findByPK($args[0]);

        if (!$tenant) {
            exit("No tenant found");
        }



// Some constants that we will use:
        $username = $tenant->cicero_user;
        $password = $tenant->cicero_password;

        function get_response($url, $postfields = '') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
            if ($postfields !== ''):
                curl_setopt($ch, CURLOPT_POST, True);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            endif;
            $json = curl_exec($ch);
            curl_close($ch);
            return json_decode($json);
        }

// Obtain a token:
        $response = get_response('http://cicero.azavea.com/v3.1/token/new.json', "username=$username&password=$password");

// Check to see if the token was obtained okay:
        if ($response->success != True):
            exit('Could not obtain token.');
        endif;

// The token and user obtained are used for other API calls:
        $cicero_token = $response->token;
        $cicero_user = $response->user;

        if (empty($cicero_token) || empty($cicero_user))
            exit('Could not obtain token.');


        $connection = Yii::app()->db;

        $transaction = $connection->beginTransaction();

        try {
            $opt = new Option();
            $opt->sessionTenantId = $tenantId;
            $opt->upsert('cicero_token', $cicero_token);
            $opt = new Option();
            $opt->sessionTenantId = $tenantId;
            $opt->upsert('cicero_user', $cicero_user);
            $transaction->commit();
            $error = false;
        } catch (CDbException $e) {
            error_log("Error. Rolling back: " . $e->getMessage());
            try {
                $transaction->rollback();
            } catch (Exception $e) {
                error_log("Could not rollback: " . $e->getMessage());
                $error = true;
            }
            $error = true;
        } catch (Exception $e) {
            error_log("result: " . $e->getMessage);
            $error = true;
        }

        if ($error) {
            exit('could not generate or save the cicero token');
        } else {
            exit('cicero token generated for: ' . $username . ' => ' . $cicero_token);
        }
    }

}