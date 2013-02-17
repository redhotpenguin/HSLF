<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this push message?')), '');
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Push Messages';
$this->secondaryNav['url'] =array('pushMessage/index');
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

    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'placement' => 'left',
        'tabs' => array(
            array('label' => 'Push Message', 'content' => $pushMessageTab, 'active' => true),
            array('label' => 'Tags', 'content' => $tagsTab),
        ),
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>


    <div class="clearfix"></div>
    <hr/>
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