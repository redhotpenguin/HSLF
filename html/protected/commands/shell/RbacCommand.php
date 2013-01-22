<?php

class RbacCommand extends CConsoleCommand {

    private $authManager;

    public function getHelp() {
        return <<<EOD
USAGE
  rbac
DESCRIPTION
  This command generates an initial RBAC authorization hierarchy.
EOD;
    }
    

   
    private function getItem($name){
        return $this->authManager->getAuthItem($name);
    }

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args) {

        $adminRole = 'admin';
        $authenticatedRole = 'authenticated';


        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->authManager = Yii::app()->authManager) === null) {
            echo "Error: an authorization manager, named 'authManager' must be configured to use this command.\n";
            echo "If you already added 'authManager' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }

        $auth = Yii::app()->authManager;


        try {
            // create default roles
            $bizRule = 'return !Yii::app()->user->isGuest;';
            $auth->createRole($authenticatedRole, 'authenticated user', $bizRule);
            $bizRule = 'return Yii::app()->user->name === "admin";';

            $auth->createRole($adminRole, 'admin user', $bizRule);
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("Roles already exists. \n");
            }
            else
                throw $e;
        }


        try {
            $auth->createOperation('createTag', 'Create a tag');
            $auth->createOperation('readTag', 'Read a tag');
            $auth->createOperation('updateTag', 'Update a tag');
            $auth->createOperation('deleteTag', 'Delete a tag');
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("Tag Tasks already exists. \n");
            }
            else
                throw $e;
        }

        try {
            $task = $auth->createTask('manageTag', 'Manage Tags', null); // no biz rule
            $task->addChild('createTag');
            $task->addChild('readTag');
            $task->addChild('updateTag');
            $task->addChild('deleteTag');
            
            $authenticatedRole = $this->getItem('authenticated');
            
            $authenticatedRole->addChild('manageTag');
            
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("Tag Role already exists. \n");
            }
            else
                throw $e;
        }
    }

}