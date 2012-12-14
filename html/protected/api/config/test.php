<?php

return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(
            'import' => array(
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

                'edms' => array(
                    'class' => 'EDMSConnection',
                    'server' => 'mongodb://localhost:27017',
                    'dbName' => 'test',
                    'options' => array(
                        'db' => 'test',
                        'username' => 'admin',
                        'password' => 'admin')
                ),
            ),
            'behaviors' => array(
                'edms' => array(
                    'class' => 'EDMSBehavior'
                )
            ),
                )
);
