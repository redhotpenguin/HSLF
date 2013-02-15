<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />


        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/main.css" />

        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/form.css" /> 

        <?php
        Yii::app()->bootstrap->register();
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>

    <body>
        <div id="menu-top" class="clearfix">
            <?php
            $items = array();
            // if user is logged in
            if (Yii::app()->user->id):
                $tenant = Yii::app()->user->getLoggedInUserTenant();


                $tenantUserId = Yii::app()->user->getLoggedInTenantUserId();


                // if user has a tenant selected
                if ($tenant) {
                    $publishingItems = array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => array(
                            '---',
                            array('label' => 'Content', 'url' => '#', 'items' => array(
                                    array('label' => 'Ballot Items', 'url' => array('/item/index'), 'visible' => Yii::app()->authManager->checkAccess('manageBallotItems', $tenantUserId)),
                                    array('label' => 'Organizations', 'url' => array('/organization/index/'), 'visible' => Yii::app()->authManager->checkAccess('manageOrganizations', $tenantUserId)),
                                    array('label' => 'Scorecard Items', 'url' => array('/scorecardItem/index'), 'visible' => Yii::app()->authManager->checkAccess('manageScorecardItems', $tenantUserId)),
                                    array('label' => 'Votes', 'url' => array('/vote/index'), 'visible' => Yii::app()->authManager->checkAccess('manageVotes', $tenantUserId)),
                                    array('label' => 'Tags', 'url' => array('/tag'), 'visible' => Yii::app()->authManager->checkAccess('manageTags', $tenantUserId)),
                            )),
                        ),
                    );

                    $applicationItems = array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => array(
                            '---',
                            array(
                                'label' => 'Mobile Application',
                                'url' => '#',
                                'items' => array(
                                    array('label' => 'Mobile Users', 'url' => array('/mobileUser'), 'visible' => Yii::app()->authManager->checkAccess('manageMobileUsers', $tenantUserId)),
                                    array('label' => 'Alert types', 'url' => array('/alertType'), 'visible' => Yii::app()->authManager->checkAccess('manageAlertTypes', $tenantUserId)),
                                    array('label' => 'Payloads', 'url' => array('/Payload/index'), 'visible' => Yii::app()->authManager->checkAccess('managePayloads', $tenantUserId)),
                                    array('label' => 'Push Messages', 'url' => array('/pushMessage/index'), 'visible' => Yii::app()->authManager->checkAccess('managePushMessages', $tenantUserId)),
                                    array('itemOptions' => array('id' => 'external_item'), 'label' => 'Urban Airship', 'linkOptions' => array('target' => '_blank'), 'url' => $tenant->ua_dashboard_link, 'visible' => Yii::app()->authManager->checkAccess('managePushMessages', $tenantUserId)),
                                    array('label' => 'Options', 'url' => array('/option'), 'visible' => Yii::app()->authManager->checkAccess('manageOptions', $tenantUserId)),
                                ),
                        )),
                    );

                    array_push($items, $publishingItems);
                    array_push($items, $applicationItems);



                    $brand = $tenant->display_name;
                    $brandUrl = '/client/' . $tenant->name;
                } else {
                    // user connected but no tenant selected
                    $brandUrl = '/client/';
                    $brand = 'Winning Mark Mobile';


                    
                    if (Yii::app()->authManager->checkAccess('admin', $tenantUserId)) {
                        $adminItems = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Admin (shared data)',
                                    'url' => '#',
                                    'items' => array(
                                        array('label' => 'Tenants', 'url' => array('/tenant/index')),
                                        '',
                                        array('label' => 'States', 'url' => array('/state/index')),
                                        array('label' => 'Districts', 'url' => array('/district/index')),
                                        '',
                                        array('label' => 'Offices', 'url' => array('/office/index')),
                                        array('label' => 'Parties', 'url' => array('/party/index')),
                                        array('label' => 'Recommendations', 'url' => array('/recommendation/index')),
                                        '',
                                        array('label' => 'Users', 'url' => array('/user/index')),
                                        '',
                                        array('label' => 'Import', 'url' => array('/import/index')),
                                    ),
                            )),
                        );
                        array_push($items, $adminItems);
                    }
                }

                $loginItems = array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        '---',
                        array('label' => 'Account (' . Yii::app()->user->name . ')', 'url' => '#', 'items' => array(
                                array(
                                    'label' => 'My Projects',
                                    'url' => '/client/',
                                ),
                                array(
                                    'label' => 'Account Settings',
                                    'url' => '/client/settings',
                                ),
                                array(
                                    'label' => 'Log Out',
                                    'url' => '/client/logout',
                                )
                        ))),
                );


                array_push($items, $loginItems);

            else: // else user is not logged in
                $brandUrl = '/client/';
                $brand = 'Winning Mark Mobile';
            endif;



            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse',
                'brand' => $brand,
                'brandUrl' => $brandUrl,
                'id' => 'main_menu',
                'items' => $items,
            ));
            ?>
        </div>

        <div id="container" class="container">

            <div id="wrap">
                <?php
                $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'homeLink' => CHtml::link('Dashboard', array('./'))
                ));
                ?><!-- breadcrumbs -->



                <div id="main"  class="container clear-top" >
                    <div class="row-fluid">

                        <div class="span12">

                            <?php
                            echo $content;


                            if (Yii::app()->user->hasFlash('success')):
                                ?>
                                <div class="update_box btn-success">
                                    <?php echo Yii::app()->user->getFlash('success'); ?>
                                </div>
                                <?php
                            endif;

                            if (Yii::app()->user->hasFlash('error')):
                                $flashMessages = Yii::app()->user->getFlashes();
                                if ($flashMessages) {
                                    echo '<div class="flashes">';
                                    foreach ($flashMessages as $key => $message) {
                                        echo '<div class="update_box btn-danger flash-' . $key . '">' . $message . "</div>\n";
                                    }
                                    echo '</div>';
                                }
                            endif;
                            ?>

                        </div>
                    </div>

                </div>
            </div><!-- page -->

        </div><!-- container-->
        <footer class="footer" id="footer"> 
            Copyright &copy; <?php echo date('Y'); ?> by Winning Mark - All Rights Reserved
        </footer><!-- footer -->


    </body>
</html>
