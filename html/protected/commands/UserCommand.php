<?php

class UserCommand extends EConsoleCommand {

    public function run($args) {


        $this->println("Add a new admin user");

        $tenantId = $this->promptString('Tenant ID');


        $userName = $this->promptString('Username (no spaces)');

        $email = $this->promptString('Email');

        $this->println("************************************************");

        $continue = $this->promptString("Save new user?", "yes/no");

        if ($continue !== "yes") {
            $this->println("Terminating.");
            return;
        }

        $user = new User();
        $password = $this->getRandomString(12);

        $user->username = $userName;
        $user->password = $password;
        $user->repeat_password = $password;
        $user->tenant_id = $tenantId;
        Yii::app()->params['current_tenant_id'] = $tenantId;

        $user->email = $email;
        $user->role = "admin";

        try {
            if ($user->save()) {
                $this->println("User successfully added");
                $this->println("ID:" . $user->id);
                $this->println("Password: " . $password);
            } else {
                $this->printlnError("Could not add user: ");
                if ($user->errors) {
                    foreach ($user->errors as $field => $error) {
                        $this->println("  => " . $error[0]);
                    }
                }
            }
        } catch (Exception $e) {
            $this->printlnError("Could not add user: ");
            $this->println($e->getMessage());
        }
    }

    public function getHelp() {
        return <<<EOD

DESCRIPTION:
Add a new user
  
EOD;
    }

    private function getRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_!.';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

}
