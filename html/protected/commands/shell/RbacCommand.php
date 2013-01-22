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

    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args) {


        $this->initializeAuthManager();

        $this->resetAll();

        $this->createRoles();


        $publisherCrudAndTasks = array(
            'BallotItem',
            'BallotItemNews',
            'Organization',
            'ScorecardItem',
            'Vote',
            'SharePayload',
            'AlertType',
            'Option',
            'Tag',
            'MobileUser',
        );


        // assign basic tasks and operation to the 'publisher' role
        foreach ($publisherCrudAndTasks as $ct) {
            $this->addCrudOperation($ct);

            $this->addCrudTask($ct);

            $this->assignTaskToRole($ct . 's', 'publisher');
        }


        // assign tasks and operation to the 'admin' role
        $adminCrudAndTask = array(
            'State',
            'District',
            'User',
            'Party',
            'Recommendation',
        );
        foreach ($adminCrudAndTask as $ct) {
            $this->addCrudOperation($ct);

            $this->addCrudTask($ct);

            $this->assignTaskToRole($ct . 's', 'admin');
        }

        // assign publisher role to admin role
        $this->assignRoleToRole('publisher', 'admin');

        $this->authManager->assign('admin', 1);
        $this->authManager->assign('publisher', 14);
    }

    private function initializeAuthManager() {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->authManager = Yii::app()->authManager) === null) {
            echo "Error: an authorization manager, named 'authManager' must be configured to use this command.\n";
            echo "If you already added 'authManager' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }

        $this->authManager = Yii::app()->authManager;
    }

    private function getItem($name) {
        return $this->authManager->getAuthItem($name);
    }

    private function createRoles() {
        $adminRole = 'admin';
        $publisherRole = 'publisher';


        try {
            // create  roles
            $this->authManager->createRole($publisherRole, 'publisher user'); // 
            $bizRule = 'return Yii::app()->user->role === "admin";';
            $this->authManager->createRole($adminRole, 'admin user');
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("Roles already exists. \n");
            }
            else
                throw $e;
        }
    }

    private function addCrudOperation($name) {
        try {
            $this->authManager->createOperation('create' . $name, 'Create a ' . $name);
            $this->authManager->createOperation('read' . $name, 'Read a ' . $name);
            $this->authManager->createOperation('update' . $name, 'Update a ' . $name);
            $this->authManager->createOperation('delete' . $name, 'Delete a ' . $name);
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("$s Tasks already exists. \n", $name);
            }
            else
                throw $e;
        }
    }

    private function addCrudTask($name) {
        try {
            $task = $this->authManager->createTask('manage' . $name . 's', 'Manage ' . $name, null); // no biz rule
            $task->addChild('create' . $name);
            $task->addChild('read' . $name);
            $task->addChild('update' . $name);
            $task->addChild('delete' . $name);
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) {
                printf("Tasks already has %s \n ", $name);
            }
            else
                throw $e;
        }
    }

    private function assignTaskToRole($taskName, $role) {
        $t = $this->getItem($role);

        $t->addChild('manage' . $taskName);
    }

    private function resetAll() {
        $this->authManager->clearAll();
        $this->authManager->clearAuthAssignments();
    }

    private function assignRoleToRole($childRole, $parentRole) {
        $parentRole = $this->getItem($parentRole);
        $parentRole->addChild($childRole);
    }

}