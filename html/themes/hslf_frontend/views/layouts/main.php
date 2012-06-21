<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <link rel="stylesheet" type="text/css" href="/themes/hslf_frontend/css/main.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    </head>

    <body>

        <div id ="header">
            <?php
            echo '<a href="/">' . Yii::app()->name . '</a>';
            $this->widget('SearchBlock', array(
            ));
            ?>

        </div>

        <div id="wrap">
            <div id="page">
                <div class="<?php echo $this->getId(); ?>">
                    <div class="<?php echo $this->getAction()->getId(); ?>" >

                        <div id="content">
                            <?php echo $content; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div id="sidebar">
            </div>
        </div>


        <div id="footer">
            Copyright © 2012
        </div>




    </body>
</html>