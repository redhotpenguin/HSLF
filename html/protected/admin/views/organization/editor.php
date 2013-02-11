<?php
$navBarItems = array(
    array('label' => 'Manage', 'url' => array('index')),
);

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this organization?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Organizations',
    'brandUrl' => array('organization/index'),
    'htmlOptions' => array('class' => 'subnav'),
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navBarItems
        ),
    ),
));
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'organization-form',
        'enableAjaxValidation' => false,
            ));

    $orgTab = $this->renderPartial('tabs/_tab_organization', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $detailTab = $this->renderPartial('tabs/_tab_detail', array(
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
            array('label' => 'Organization', 'content' => $orgTab, 'active' => true),
            array('label' => 'Details', 'content' => $detailTab),
            array('label' => 'Tags', 'content' => $tagsTab),
        ),
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>


    <div class="clearfix"></div>

    <hr/>


    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));
        ?>
    </div>

    <?php
    $this->endWidget();

    if (getParam('updated') == '1' || getParam('created') == '1') {
        echo '<div class="update_box btn-success">Organization Saved</div>';
    }
    ?>

</div><!-- form -->