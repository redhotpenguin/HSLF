<?php
/* @var $this TenantController */
/* @var $model Tenant */

$this->secondaryNav['name'] = 'Tenants';
$this->secondaryNav['url'] = array('tenant/index');
?>

<?php
/* @var $this TenantController */
/* @var $model Tenant */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tenant-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <h4>Tenant Information</h4>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('class' => 'span9', 'maxlength' => 32)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name', array('class' => 'span9', 'maxlength' => 256)); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'creation_date'); ?>
        <?php echo $form->textField($model, 'creation_date', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'creation_date'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'web_app_url'); ?>
        <?php echo $form->textField($model, 'web_app_url', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'web_app_url'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <hr/>

    <h4>API Configuration</h4>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'api_key'); ?>
        <?php echo $form->textField($model, 'api_key', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'api_key'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'api_secret'); ?>
        <?php echo $form->textField($model, 'api_secret', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'api_secret'); ?>
    </div>


    <hr/>
    <h4>Cicero Configuration</h4>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'cicero_user'); ?>
        <?php echo $form->textField($model, 'cicero_user', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'cicero_user'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'cicero_password'); ?>
        <?php echo $form->textField($model, 'cicero_password', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'cicero_password'); ?>
    </div>

    <hr/>
    <h4>Urban Airship Configuration</h4>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'ua_api_key'); ?>
        <?php echo $form->textField($model, 'ua_api_key', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'ua_api_key'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($model, 'ua_api_secret'); ?>
        <?php echo $form->textField($model, 'ua_api_secret', array('class' => 'span9',)); ?>
        <?php echo $form->error($model, 'ua_api_secret'); ?>
    </div>

    <hr/>
    <h4>Other Settings</h4>
    <div class="row-fluid">
        <?php echo $form->labelEx($tenantSetting, 'analytics_link'); ?>
        <?php echo $form->textField($tenantSetting, 'analytics_link', array('class' => 'span9',)); ?>
        <?php echo $form->error($tenantSetting, 'analytics_link'); ?>
    </div>

    <div class="row-fluid">
        <?php echo $form->labelEx($tenantSetting, 'ios_link'); ?>
        <?php echo $form->textField($tenantSetting, 'ios_link', array('class' => 'span9',)); ?>
        <?php echo $form->error($tenantSetting, 'ios_link'); ?>
    </div>
    <div class="row-fluid">
        <?php echo $form->labelEx($tenantSetting, 'android_link'); ?>
        <?php echo $form->textField($tenantSetting, 'android_link', array('class' => 'span9',)); ?>
        <?php echo $form->error($tenantSetting, 'android_link'); ?>
    </div>

    <div class="row-fluid buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>
    <?php
    $this->endWidget();
    ?>

</div><!-- form -->