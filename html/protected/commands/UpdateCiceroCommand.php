<?php

class UpdateCiceroCommand extends CConsoleCommand {

    public function run($args) {
        // Some constants that we will use:
        $username = CICERO_USERNAME;
        $password = CICERO_PASSWORD;

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
            // upsert_option is a custom postesgsql function
            $update_cicero_token_query = "SELECT upsert_option('cicero_token', '$cicero_token')";
            $update_cicero_user_query = "SELECT upsert_option('cicero_user', '$cicero_user')";

            $connection->createCommand($update_cicero_token_query)->execute();
            $connection->createCommand($update_cicero_user_query)->execute();

            // commit the transaction
            $transaction->commit();
            $result = true;
        } catch (Exception $e) {
            $result = $e->getMessage();
            echo $result;
            return;
        }

        echo 'cicero token generated for: ' . $username. ' => '.$cicero_token;
    }

}