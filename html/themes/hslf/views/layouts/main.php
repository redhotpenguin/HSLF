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

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page">

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

            <div id="column_menu">

                <?php
                if (!Yii::app()->user->isGuest):
                    ?>

                    <div id="menu_publishing">
                        <p class="header_menu">Publishing</p>
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                array('label' => 'State', 'url' => array('/state'), 'visible' => !Yii::app()->user->isGuest),
                                array('label' => 'District', 'url' => array('/district'), 'visible' => !Yii::app()->user->isGuest),
                                array('label' => 'Candidate', 'url' => array('/candidate'), 'visible' => !Yii::app()->user->isGuest),
                            ),
                        ));
                        ?>
                    </div>


                    <div id="menu_messaging">
                        <p class="header_menu">Messaging center</p>
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                array('label' => 'User Alerts', 'url' => array('/user_alert'), 'visible' => !Yii::app()->user->isGuest),
                                array('label' => 'Push Notifications', 'url' => array('/pushNotifications'), 'visible' => !Yii::app()->user->isGuest),
                            ),
                        ));
                        ?>
                    </div>

                    <div id="menu_admin">
                        <p class="header_menu">Administration</p>
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                array('label' => 'Application Users', 'url' => array('/application_users'), 'visible' => !Yii::app()->user->isGuest),
                                array('label' => 'Users', 'url' => array('/user'), 'visible' => !Yii::app()->user->isGuest),
                            ),
                        ));
                        ?>
                    </div>

                    <?php
                endif;
                ?>   

            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs) && !Yii::app()->user->isGuest): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by Winning Mark.<br/>
                All Rights Reserved.<br/>

            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
