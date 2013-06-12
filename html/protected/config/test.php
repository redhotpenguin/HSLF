<?php

$clientDirectory = dirname(dirname(__FILE__));
$appDirectory = dirname($clientDirectory);

Yii::setPathOfAlias('backend', $clientDirectory);

return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(
            'import' => array(
                'admin.models.*',
                'application.shared.models.dal.*', // data access logic classes
                'application.shared.models.bll.*', // business  logic classes
                'admin.components.*',
                'application.models.*',
                'application.components.*',
                'ext.directmongosuite.components.*',
            ),
            'components' => array(
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                ),
            /* uncomment the following to provide test database connection
              'db'=>array(
              'connectionString'=>'DSN for test database',
              ),
             */
            ),
            'edms' => array(
                'class' => 'EDMSConnection',
                'server' => 'mongodb://localhost:27017',
                'dbName' => 'test',
                'options' => array(
                    'db' => 'test',
                    'username' => 'admin',
                    'password' => 'admin')
            ),
            'behaviors' => array(
                'edms' => array(
                    'class' => 'EDMSBehavior'
                )
            ),
                )
);
