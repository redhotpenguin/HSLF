<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <!-- blueprint CSS framework
        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/print.css" media="print" /> 
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/main.css" />

        <link rel="stylesheet" type="text/css" href="/themes/dashboard/css/form.css" /> 

        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('/js/superfish.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/js/jquery.hoverIntent.minified.js', CClientScript::POS_HEAD);
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>


        <script type="text/javascript"> 
            jQuery(document).ready(function($){
                $('ul#main_menu').superfish({
                    autoArrows: false
                });
 
            });
        </script>

    </head>

    <body>
        <div id="menu-top" class="clearfix">
            <?php
            $items = array();
            // if user is logged in
            if (Yii::app()->user->id):
                $tenant = Yii::app()->user->getCurrentTenant();


                // if user has a tenant selected
                if ($tenant) {
                    $publishingItems = array(
                        'class' => 'bootstrap.widgets.BootMenu',
                        'items' => array(
                            '---',
                            array('label' => 'Publishing', 'url' => '#', 'items' => array(
                                    array('label' => 'Ballot Items', 'url' => array('/item/admin')),
                                    array('label' => 'Organizations', 'url' => array('/organization/admin/')),
                                    array('label' => 'Scorecard Items', 'url' => array('/scorecardItem/admin')),
                                    '',
                                    array('label' => 'Votes', 'url' => array('/vote/admin')),
                                    '',
                                    array('label' => 'Image Upload', 'url' => array('/upload')),
                                    '',
                                    array('label' => 'Share Payloads', 'url' => array('/sharePayload/admin')),
                                    '',
                                    array('itemOptions' => array('id' => 'external_item'), 'label' => 'Urban Airship', 'linkOptions' => array('target' => '_blank'), 'url' => $tenant->ua_dashboard_link),
                            )),
                        ),
                    );

                    $applicationItems = array(
                        'class' => 'bootstrap.widgets.BootMenu',
                        'items' => array(
                            '---',
                            array(
                                'label' => 'Application Manager',
                                'url' => '#',
                                'items' => array(
                                    array('label' => 'Alert types', 'url' => array('/alertType'), 'visible'),
                                    array('label' => 'Options', 'url' => array('/option'), 'visible'),
                                    array('label' => 'Tags', 'url' => array('/tag')),
                                    array('label' => 'Mobile Users', 'url' => array('/mobileUser')),
                                ),
                        )),
                    );

                    if (Yii::app()->authManager->checkAccess('publisher', Yii::app()->user->id)) {
                        array_push($items, $publishingItems);
                        array_push($items, $applicationItems);
                    }


                    $brand = $tenant->display_name;
                    $brandUrl = '/admin/' . $tenant->name;
                } else {
                    // user connected but no tenant selected
                    $brandUrl = '/admin/';
                    $brand = 'Winning Mark Mobile';


                    if (Yii::app()->authManager->checkAccess('admin', Yii::app()->user->id)) {

                        $adminItems = array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Admin (shared data)',
                                    'url' => '#',
                                    'items' => array(
                                        array('label' => 'Tenants', 'url' => array('/tenant/admin')),
                                        '',
                                        array('label' => 'States', 'url' => array('/state/admin')),
                                        array('label' => 'Districts', 'url' => array('/district/admin')),
                                        '',
                                        array('label' => 'Offices', 'url' => array('/office/admin')),
                                        array('label' => 'Parties', 'url' => array('/party/admin')),
                                        array('label' => 'Recommendations', 'url' => array('/recommendation/admin')),
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
                    'class' => 'bootstrap.widgets.BootMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        '---',
                        array('label' => 'Account (' . Yii::app()->user->name . ')', 'url' => '#', 'items' => array(
                                array(
                                    'label' => 'My Projects',
                                    'url' => '/admin/',
                                ),
                                array(
                                    'label' => 'Account Settings',
                                    'url' => '/admin/settings',
                                ),
                                array(
                                    'label' => 'Log Out',
                                    'url' => '/admin/logout',
                                )
                        ))),
                );


                array_push($items, $loginItems);

            else: // else user is not logged in
                $brandUrl = '/admin/';
                $brand = 'Winning Mark Mobile';
            endif;



            $this->widget('bootstrap.widgets.BootNavbar', array(
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
                $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'homeLink' => CHtml::link('Dashboard', array('./'))
                ));
                ?><!-- breadcrumbs -->



                <div id="main"  class="container clear-top" >
                    <div class="row">

                        <div style="float:left;" class="span10">

                            <?php echo $content; ?>

                        </div>

                        <div style="float:left;" class="span2">
                            <div id="sidebar">

                                <?php
                                $this->widget('bootstrap.widgets.BootMenu', array(
                                    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
                                    'stacked' => true, // whether this is a stacked menu
                                    'items' => $this->menu,
                                    'htmlOptions' => array('class' => 'nav nav-pills nav-stacked'),
                                ));
                                ?>

                            </div>
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
