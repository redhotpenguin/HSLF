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
                    'server' => MONGODB_HOST,
                    'dbName' => MONGODB_NAME,
                    'options' => array(
                        'db' => MONGODB_NAME,
                        'username' => MONGODB_USER,
                        'password' => MONGODB_PASS)
                )
            ),
            'behaviors' => array(
                'edms' => array(
                    'class' => 'EDMSBehavior'
                )
            ),
            'params' => array(
                'dateFormat' => 'Y-m-d H:i:s',
                'mongodb_ack_level' => MONGODB_ACK_LEVEL
            ),
                )
);
