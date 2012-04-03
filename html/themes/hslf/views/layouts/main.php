<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="/themes/hslf/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="/themes/hslf/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="/themes/hslf/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="/themes/hslf/css/main.css" />
        <link rel="stylesheet" type="text/css" href="/themes/hslf/css/form.css" />
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

        <div class="container" id="page">
            <div class="<?php echo $this->getId(); ?>">
                <div class="<?php echo $this->getAction()->getId(); ?>" >


                    <div id="header">
                        <div id="logo"><a href="/"><?php echo CHtml::encode(Yii::app()->name); ?></a></div>

                        <div id="menu_auth">
                            <?php
                            $this->widget('zii.widgets.CMenu', array(
                                'items' => array(
                                    array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                                    array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                                ),
                            ));
                            ?>
                        </div>

                    </div><!-- header -->
                    <?php
                    if (Yii::app()->user->id):
                        ?>

                        <div id="menu-top" class="clearfix">
                            <?php
                            $this->widget('zii.widgets.CMenu', array(
                                'activeCssClass' => 'active',
                                'activateParents' => true,
                                'id' => 'main_menu',
                                'items' => array(
                                    array(
                                        'label' => 'Publishing',
                                        'itemOptions' => array('id' => 'itemPublishing'),
                                        'url' => array('/site/publishing'),
                                        'visible' => !Yii::app()->user->isGuest,
                                        'items' => array(
                                            array('label' => 'State', 'url' => array('/state'), 'visible' => !Yii::app()->user->isGuest),
                                            array('label' => 'District', 'url' => array('/district'), 'visible' => !Yii::app()->user->isGuest),
                                            array('label' => 'Candidate', 'url' => array('/candidate'), 'visible' => !Yii::app()->user->isGuest),
                                        ),
                                    ),
                                    array(
                                        'label' => 'Messaging Center',
                                        'url' => array('/site/messaging'),
                                        'visible' => !Yii::app()->user->isGuest,
                                        'itemOptions' => array('id' => 'itemMessaging'),
                                        'items' => array(
                                            array('label' => 'User Alerts', 'url' => array('/user_alert'), 'visible' => !Yii::app()->user->isGuest),
                                            array('label' => 'Push Notifications', 'url' => array('/pushNotifications'), 'visible' => !Yii::app()->user->isGuest),
                                        ),
                                    ),
                                    array(
                                        'label' => 'Administration',
                                        'url' => array('/site/administration'),
                                        'visible' => !Yii::app()->user->isGuest,
                                        'itemOptions' => array('id' => 'itemAdministration'),
                                        'items' => array(
                                            array('label' => 'Application Users', 'url' => array('/application_users'), 'visible' => !Yii::app()->user->isGuest),
                                            array('label' => 'Users', 'url' => array('/user'), 'visible' => !Yii::app()->user->isGuest),
                                        ),
                                    ),
                                ),
                            ));
                            ?>
                        </div>
                        <?php
                    endif;
                    ?>


                    <?php echo $content; ?>

                    <div class="clear"></div>


                    <div id="footer">
                        Copyright &copy; <?php echo date('Y'); ?> by Winning Mark.<br/>
                        All Rights Reserved.<br/>

                    </div><!-- footer -->
                </div>
            </div>
        </div><!-- page -->


    </body>
</html>
