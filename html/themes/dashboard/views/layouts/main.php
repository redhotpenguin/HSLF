<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <!-- blueprint CSS framework -->
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

        <?php
        if (Yii::app()->user->id):
            $tenant = TenantAccount::model()->findByPk(Yii::app()->user->tenant_account_id);
            $tenantOption = TenantOption::model()->findByAttributes(array("tenant_account_id" => $tenant->id));
            ?>


            <div id="menu-top" class="clearfix">
                <?php
                $this->widget('bootstrap.widgets.BootNavbar', array(
                    'brand' => $tenant->display_name,
                    'brandUrl' => '/admin/',
                    'id' => 'main_menu',
                    'items' => array(
                        array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'items' => array(
                                '---',
                                array('label' => 'Publishing', 'url' => '#', 'items' => array(
                                        array('label' => 'States', 'url' => array('/state/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Districts', 'url' => array('/district/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Votes', 'url' => array('/vote/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Recommendations', 'url' => array('/recommendation/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Ballot Items', 'url' => array('/ballotItem/admin'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Scorecard Items', 'url' => array('/scorecardItem/admin'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Offices', 'url' => array('/office/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Parties', 'url' => array('/party/admin'), 'visible' => isAdmin()),
                                        array('label' => 'Endorsers', 'url' => array('/endorser/admin/'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Image Upload', 'url' => array('/upload'), 'visible' => !Yii::app()->user->isGuest),
                                        array('itemOptions' => array('id' => 'external_item'), 'label' => 'Rich Push Notifications', 'linkOptions' => array('target' => '_blank'), 'url' => $tenantOption->ua_dashboard_link),
                                )),
                            ),
                        ),
                        array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Application Manager',
                                    'url' => '#',
                                    'items' => array(
                                        array('label' => 'Tags', 'url' => array('/tag'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Alert types', 'url' => array('/alertType'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Options', 'url' => array('/option'), 'visible' => !Yii::app()->user->isGuest),
                                    ),
                            )),
                        ),
                        array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Administration',
                                    'url' => '#',
                                    'items' => array(
                                        array('label' => 'Users', 'url' => array('/user'), 'visible' => !Yii::app()->user->isGuest),
                                        array('label' => 'Import', 'url' => array('/import'), 'visible' => isAdmin()),
                                    ),
                            )),
                        ),
                        array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'htmlOptions' => array('class' => 'pull-right'),
                            'items' => array(
                                '---',
                                array(
                                    'label' => 'Logout (' . Yii::app()->user->name . ')',
                                    'url' => '/admin/site/logout',
                                    'visible' => !Yii::app()->user->isGuest,
                            )),
                        ),
                    ),
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


endif;
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
