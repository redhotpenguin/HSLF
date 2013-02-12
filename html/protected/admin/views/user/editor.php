<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this user?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Users',
    'brandUrl' => array('user/index'),
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));

$model->password = '';
?>



<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
            ));


    $userTab = $this->renderPartial('tabs/_tab_user', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $tenantsTab = $this->renderPartial('tabs/_tab_tenants', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'placement' => 'left',
        'tabs' => array(
            array('label' => 'User', 'content' => $userTab, 'active' => true),
            array('label' => 'Tenants', 'content' => $tenantsTab),
        ),
    ));
    ?>
    <div class="clearfix"></div>


    <?php echo $form->errorSummary($model); ?>



    <hr/>
    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>



    <?php $this->endWidget(); ?>

    <div class="text-error">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<b>' . $message . "</b>";
        }
        ?>
    </div>

</div><!-- form -->