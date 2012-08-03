<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="robots" content="noindex" />
     
        <link rel="stylesheet" type="text/css" href="/themes/frontend/css/main.css" />

        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
  
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    </head>

    <body>

        <div id ="header">
   

        </div>

        <div id="wrap">
            <div id="page">
                <div class="<?php echo $this->getId(); ?>">
                    <div class="<?php echo $this->getAction()->getId(); ?>" >

                        <div id="content">
                            <h1>Mobile Voter guide</h1>
                            <?php echo $content; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div id="sidebar">
            </div>
        </div>


        <div id="footer">
        </div>




    </body>
</html>