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
            $this->widget('bootstrap.widgets.BootNavbar', array(
                'brand' => Yii::app()->name,
                'brandUrl' => '/',
                'id' => 'main_menu',
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.BootMenu',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'items' => array(
                            '---',
                            array(
                                'label' => 'Login',
                                'url' => '/admin',
                            ),
                    ))),
            ));
            ?>
        </div>

        <div id="container" class="container">




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

    </body>
</html>