<?php

class RbacCommand extends CConsoleCommand {

    private $_authManager;

    public function getHelp() {
        return <<<EOD
USAGE
  rbac

DESCRIPTION
  This command generates an initial RBAC authorization hierarchy.

EOD;
    }

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args) {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->_authManager = Yii::app()->authManager) === null) {
            echo "Error: an authorization manager, named 'authManager' must be con-figured to use this command.\n";
            echo "If you already added 'authManager' component in application con-figuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }

        //provide the oportunity for the use to abort the request
        echo "This command will create two roles: publisher and Reader and the following premissions:\n";
        echo "create, read, update and delete candidate\n";
        echo "create, read, update and delete notification\n";
        echo "Would you like to continue? [Yes|No] ";

        //check the input from the user and continue if they indicated yes to the above question
        if (!strncasecmp(trim(fgets(STDIN)), 'y', 1)) {
            //first we need to remove all operations, roles, child relationship and as-signments
            $this->_authManager->clearAll();

            //create the lowest level operations for users
            $this->_authManager->createOperation("createCandidate", "create a new candidate");
            $this->_authManager->createOperation("readCandidate", "read candidate");
            $this->_authManager->createOperation("updateCandidate", "update a candidate");
            $this->_authManager->createOperation("deleteCandidate", "remove a candidate");

            $this->_authManager->createOperation("createPushNotifications", "create a new push notification");
            $this->_authManager->createOperation("readPushNotifications", "read a push notification");
            $this->_authManager->createOperation("updatePushNotifications", "update a push notification");
            $this->_authManager->createOperation("deletePushNotifications", "remove a push notification");



            //create the reader role and add the appropriate permissions as children to this role
            $role = $this->_authManager->createRole("reader");
            $role->addChild("readPushNotifications");
            $role->addChild("readCandidate");

            //create the publisher role, and add the appropriate permissions, as well as the reader role itself, as children
            $role = $this->_authManager->createRole("publisher");
            $role->addChild("reader");
            $role->addChild("createCandidate");
            $role->addChild("updateCandidate");
            $role->addChild("deleteCandidate");

            $role->addChild("createPushNotifications");
            $role->addChild("updatePushNotifications");
            $role->addChild("deletePushNotifications");



            //provide a message indicating success
            echo "Authorization hierarchy successfully generated.";
        }
    }

}
