<?php

class TenantCommand extends EConsoleCommand {

    public function run($args) {

        $name = $this->promptString('Name (no spaces)');

        $displayName = $this->promptString('Display name');

        $email = $this->promptString('Email');

        $webAppUrl = $this->promptString('Web App URL');

        $uaLink = $this->promptString('Urban Airship Link');

        $uaApiKey = $this->promptString('Urban Airship Application key:');

        $uaApiSecret = $this->promptString('Urban Airship Application secret(master)');

        $ciceroUSer = $this->promptString('Cicero Username');

        $ciceroPass = $this->promptString('Cicero Password');

        $this->println("************************************************");

        $continue = $this->promptString("Save new tenant?", "yes/no");

        if ($continue !== "yes") {
            $this->println("Terminating.");
            return;
        }

        $tenant = new Tenant();

        $tenant->name = $name;
        $tenant->display_name = $displayName;
        $tenant->web_app_url = $webAppUrl;
        $tenant->creation_date = date('Y-m-d h:i:s');
        $tenant->email = $email;
        $tenant->api_key = rand(10000, 99999);
        $tenant->api_secret = md5(rand(10000, 99999));
        $tenant->ua_dashboard_link = $uaLink;
        $tenant->ua_api_key = $uaApiKey;
        $tenant->ua_api_secret = $uaApiSecret;
        $tenant->cicero_user = $ciceroUSer;
        $tenant->cicero_password = $ciceroPass;


        try {
            if ($tenant->save()) {
                $this->println("Tenant successfully added");
                $this->println("ID:" . $tenant->id);
                $this->println("API Key: " . $tenant->api_key);
                $this->println("API Secret: " . $tenant->api_secret);
            } else {
                $this->printlnError("Could not add tenant: ");
                if ($tenant->errors) {
                    foreach ($tenant->errors as $field => $error) {
                        $this->println("  => " . $error[0]);
                    }
                }
            }
        } catch (Exception $e) {
            $this->printlnError("Could not add tenant: ");
            $this->println($e->getMessage());
        }
    }

    public function getHelp() {
        return <<<EOD

DESCRIPTION:
Add a new tenant
  
EOD;
    }

}

?>
