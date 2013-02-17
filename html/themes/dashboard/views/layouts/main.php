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

        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/static/global/js/dashboard.js');
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
                    // dynamic content menu
                    $contentMenu = array();
                    $contentMenuItems = array();

                    if (Yii::app()->authManager->checkAccess('manageBallotItems', $tenantUserId))
                        array_push($contentMenuItems, array('label' => 'Ballot Items', 'url' => array('/item/index')));

                    if (Yii::app()->authManager->checkAccess('manageOrganizations', $tenantUserId))
                        array_push($contentMenuItems, array('label' => 'Organizations', 'url' => array('/organization/index')));

                    if (Yii::app()->authManager->checkAccess('manageScorecardItems', $tenantUserId))
                        array_push($contentMenuItems, array('label' => 'Scorecard Items', 'url' => array('/scorecardItem/index')));

                    if (Yii::app()->authManager->checkAccess('manageVotes', $tenantUserId))
                        array_push($contentMenuItems, array('label' => 'Votes', 'url' => array('/vote/index')));

                    if (Yii::app()->authManager->checkAccess('manageTags', $tenantUserId))
                        array_push($contentMenuItems, array('label' => 'Tags', 'url' => array('/tag/index')));



                    if (count($contentMenuItems) > 0) {
                        $contentMenu = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array('label' => 'Content', 'url' => '#', 'items' => $contentMenuItems),
                            ),
                        );
                    }


                    // dynamic mobile app menu
                    $applicationMenu = array();
                    $applicationMenuItems = array();


                    if (Yii::app()->authManager->checkAccess('manageMobileUsers', $tenantUserId))
                        array_push($applicationMenuItems, array('label' => 'Mobile Users', 'url' => array('/mobileUser/index')));

                    if (Yii::app()->authManager->checkAccess('manageAlertTypes', $tenantUserId))
                        array_push($applicationMenuItems, array('label' => 'Alert types', 'url' => array('/alertType/index')));

                    if (Yii::app()->authManager->checkAccess('managePayloads', $tenantUserId))
                        array_push($applicationMenuItems, array('label' => 'Payloads', 'url' => array('/payload/index')));

                    if (Yii::app()->authManager->checkAccess('managePushMessages', $tenantUserId))
                        array_push($applicationMenuItems, array('label' => 'Push Messages', 'url' => array('/pushMessage/index')));

                    if (Yii::app()->authManager->checkAccess('managePushMessages', $tenantUserId))
                        array_push($applicationMenuItems, array('itemOptions' => array('id' => 'external_item'), 'label' => 'Urban Airship', 'linkOptions' => array('target' => '_blank'), 'url' => $tenant->ua_dashboard_link));

                    if (Yii::app()->authManager->checkAccess('manageOptions', $tenantUserId))
                        array_push($applicationMenuItems, array('label' => 'Options', 'url' => array('/option/index')));

                    if (count($applicationMenuItems) > 0) {
                        $applicationMenu = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array('label' => 
                                    'Mobile Application', 
                                    'url' => '#', 
                                    'items' => $applicationMenuItems),
                            ),
                        );
                    }

           
                    array_push($items, $contentMenu);
                    array_push($items, $applicationMenu);

                    $brand = $tenant->display_name;
                    $brandUrl = '/client/' . $tenant->name;
                } else {
                    // user connected but no tenant selected
                    $brandUrl = '/client/';
                    $brand = 'Winning Mark Mobile';



                    if (Yii::app()->authManager->checkAccess('admin', $tenantUserId)) {
                        $adminMenu = array(
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
                        array_push($items, $adminMenu);
                    }
                }

                $loginMenu = array(
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


                array_push($items, $loginMenu);

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

        <?php
        if ($this->secondaryNav):
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'brand' => (isset($this->secondaryNav['name']) ? $this->secondaryNav['name'] : '' ),
                'brandUrl' => (isset($this->secondaryNav['url']) ? $this->secondaryNav['url'] : '#' ),
                'htmlOptions' => array('class' => 'subnav', 'id' => 'subnav'),
                'collapse' => true,
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => (isset($this->secondaryNav['items']) ? $this->secondaryNav['items'] : array() )
                    ),
                ),
            ));

        endif;
        ?>


        <div id="container" class="container">
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


        </div><!-- container-->
        <footer class="footer" id="footer"> 
            Copyright &copy; <?php echo date('Y'); ?> by Winning Mark - All Rights Reserved
        </footer><!-- footer -->


    </body>
</html>
