<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans" />

        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />


        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/main.css" />

        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/form.css" /> 


        <?php
        Yii::app()->bootstrap->register();

        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/static/dashboard/js/dashboard.js');
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

                // if user has a tenant selected
                if ($tenant) {
                    // dynamic content menu
                    $contentMenu = array();
                    $contentMenuItems = array();

                    if (Yii::app()->user->hasPermission('manageBallotItems'))
                        array_push($contentMenuItems, array('label' => 'Ballot Items', 'url' => array('/ballotItem/index')));

                    if (Yii::app()->user->hasPermission('manageOrganizations'))
                        array_push($contentMenuItems, array('label' => 'Organizations', 'url' => array('/organization/index')));

                    if (Yii::app()->user->hasPermission('manageContacts'))
                        array_push($contentMenuItems, array('label' => 'Contacts', 'url' => array('/contact/index')));

                    if (Yii::app()->user->hasPermission('manageScorecardItems'))
                        array_push($contentMenuItems, array('label' => 'Scorecard Items', 'url' => array('/scorecardItem/index')));

                    if (Yii::app()->user->hasPermission('manageVotes'))
                        array_push($contentMenuItems, array('label' => 'Votes', 'url' => array('/vote/index')));

                    if (Yii::app()->user->hasPermission('manageTags'))
                        array_push($contentMenuItems, array('label' => 'Tags', 'url' => array('/tag/index')));

                    if (Yii::app()->user->hasPermission('manageOptions'))
                        array_push($contentMenuItems, array('label' => 'Options', 'url' => array('/option/index')));

                    if (count($contentMenuItems) > 0) {
                        $contentMenu = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array('label' => 'Content', 'url' => '#', 'items' => $contentMenuItems),
                            ),
                        );
                    }


                    // communication menu (formerly called mobile application menu)
                    $applicationMenu = array();
                    $applicationMenuItems = array();


                    if (Yii::app()->user->hasPermission('manageMobileUsers'))
                        array_push($applicationMenuItems, array('label' => 'Mobile Users', 'url' => array('/mobileUser/index')));

                    if (Yii::app()->user->hasPermission('manageAlertTypes'))
                        array_push($applicationMenuItems, array('label' => 'Alert types', 'url' => array('/alertType/index')));

                    if (Yii::app()->user->hasPermission('managePayloads'))
                        array_push($applicationMenuItems, array('label' => 'Payloads', 'url' => array('/payload/index')));

                    if (Yii::app()->user->hasPermission('managePushMessages'))
                        array_push($applicationMenuItems, array('label' => 'Push Messages', 'url' => array('/pushMessage/index')));

                    if (Yii::app()->user->hasPermission('manageMobileUsers'))
                        array_push($applicationMenuItems, array('label' => 'Reports', 'url' => array('/report/index')));


                    if (count($applicationMenuItems) > 0) {
                        $applicationMenu = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array('label' =>
                                    'Communication',
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

                    if (Yii::app()->user->hasPermission('admin')) {
                        $adminMenu = array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Administration',
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
                'collapse' => true,
                'fluid' => false
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


        <div id="container" class="container clear-top">
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




        </div><!-- container-->
        <footer class="footer" id="footer"> 
            Copyright &copy; <?php echo date('Y'); ?> by Winning Mark - All Rights Reserved
        </footer><!-- footer -->


    </body>
</html>
