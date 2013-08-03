<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'New Message', 'url' => array('composer'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this push message?')), '');
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Push Notifications';
$this->secondaryNav['url'] = array('pushMessage/index');
?>



<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'push-message-form',
        'enableAjaxValidation' => false,
            ));

    $pushMessageTab = $this->renderPartial('tabs/_tab_push_message', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $tagsTab = $this->renderPartial('tabs/_tab_tags', array(
        'model' => $model,
        'form' => $form,
            ), true);
    
    $tabs = array(
        array('label' => 'Push Message', 'content' => $pushMessageTab, 'active' => true),
        array('label' => 'Tags', 'content' => $tagsTab)
    );

    if ($model->isNewRecord) {
        $tagsTab = $this->renderPartial('tabs/_tab_tags', array(
            'model' => $model,
            'form' => $form,
                ), true);


        array_push($tabs, array('label' => 'Tags', 'content' => $tagsTab));
    }

    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'placement' => 'left',
        'tabs' => $tabs
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>


    <div class="clearfix"></div>
    <hr/>

    <?php
    if (!$model->isNewRecord):
        ?>
        <div class="alert alert-info">
            Updates to push messages will not affect previously sent push notifications. Changes will only be reflected in the Alert Inbox.
        </div>
        <?php
    endif;
    ?>

    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    ));
    ?>


    <?php
    $this->endWidget();
    ?>

</div>