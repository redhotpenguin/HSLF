<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans" />

        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <!-- blueprint CSS framework -->


        <link rel="stylesheet" type="text/css" href="/themes/frontend/css/main.css" />


        <?php
        Yii::app()->bootstrap->register();
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>

    <body>
        <div id="menu-top" class="clearfix">

            <?php
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'brand' => Yii::app()->name,
                'brandUrl' => '/',
                'id' => 'main_menu',
                'type' => 'inverse',
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'items' => array(
                            '---',
                            array(
                                'label' => 'Login',
                                'url' => '/client',
                            ),
                    ))),
            ));
            ?>
        </div>

        <div id="container" class="container">
            <div id="main"  class="container clear-top" >
                <div class="row">

                    <div class="span12">

                        <?php echo $content; ?>

                    </div>

                </div> <!-- row -->

            </div> <!-- main-->

        </div><!-- container-->
        <footer class="footer" id="footer"> 
            Copyright &copy; <?php echo date('Y'); ?> by Winning Mark - All Rights Reserved
        </footer><!-- footer -->
    </body>
</html>