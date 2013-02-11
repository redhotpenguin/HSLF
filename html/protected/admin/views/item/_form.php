<?php
$tenant = Yii::app()->user->getCurrentTenant();

$siteUrl = $siteUrl = getSetting('site_url') . '/admin/' . $tenant->name;

if ($model->isNewRecord) {
    $ns = "var ns  = {site_url: '" . $siteUrl . "',share_url: '" . $tenant->web_app_url . "' };";
} else {
    $ns = "var ns  = {site_url: '" . $siteUrl . "',share_url: '" . $tenant->web_app_url . "', item_id: " . $model->id . " };";
}

Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/static/ballotitem/item.js');
?>

<div class="form">

    <?php
    $recommendation_list = CHtml::listData(Recommendation::model()->findAll(), 'id', 'value');

    if ($model->id) {
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => true,
            'stateful' => true,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'form-vertical'),
                ));
    } else {
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => false,
            'stateful' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'form-vertical'),
                ));
    }

    $itemTab = $this->renderPartial('tabs/_tab_item', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $orgTab = $this->renderPartial('tabs/_tab_organizations', array(
        'model' => $model,
        'organization_list' => $organization_list // defined in controller
            ), true);


    $detailTab = $this->renderPartial('tabs/_tab_detail', array(
        'model' => $model,
        'form' => $form,
        'recommendation_list' => $recommendation_list,
            ), true);

    $newsTab = $this->renderPartial('tabs/_tab_news', array(
        'model' => $model,
        'form' => $form,
            ), true);


    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'placement' => 'above',
        'tabs' => array(
            array('label' => 'Ballot Item', 'content' => $itemTab, 'active' => true),
            array('label' => 'Details', 'content' => $detailTab),
            array('label' => 'Organizations', 'content' => $orgTab),
            array('label' => 'News Updates', 'content' => $newsTab),
        ),
    ));
    ?>

    <hr/>
    
    <?php echo $form->errorSummary($model); 

    if (!$model->isNewRecord) {
        $url = CHtml::normalizeUrl(array(
                    'item/update',
                    'id' => $model->id,
                    'enctype' => 'multipart/form-data',
                ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'ajaxButton',
            'type' => 'primary',
            'label' => 'Save',
            'url' => $url,
            'ajaxOptions' => array(
                'type' => 'POST',
                'update' => '#targetdiv',
                'beforeSend' => 'js:function(){
                    target =$("#targetdiv");
                    target.fadeIn();
                    target.removeClass("hidden");
                    target.addClass("btn-info");
                    target.html("saving...");
                 }',
                'success' => 'js:function(response) {
               target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                 
                  if ( response == "success" ){
                         sessionStorage.setItem("ItemContent", "");
                         target.addClass("btn-success");
                         target.html( "Ballot item successfully saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save ballot item." );
                  }
                target.fadeOut(5000, function(){
                 target.removeClass("btn-danger");
                 target.removeClass("btn-success");
                });
              
                
             }',
                'error' => 'js:function(object){
              
                target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                   target.addClass("btn-danger");
         
                   target.html( "Could not save item:<br/>" + object.responseText );
             
            }',
                )));

        CHtml::ajaxSubmitButton('Save', $url, array(
            'type' => 'POST',
            'update' => '#targetdiv',
            'beforeSend' => 'js:function(){
                    target =$("#targetdiv");
                    target.fadeIn();
                    target.removeClass("hidden");
                    target.addClass("btn-info");
                    target.html("saving...");
                 }',
            'success' => 'js:function(response) {
               target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                 
                  if ( response == "success" ){
                         sessionStorage.setItem("ItemContent", "");
                         target.addClass("btn-success");
                         target.html( "Ballot item saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save ballot item." );
                  }
                target.fadeOut(5000, function(){
                 target.removeClass("btn-danger");
                 target.removeClass("btn-success");
                });
              
                
             }',
            'error' => 'js:function(object){
              
                target =$("#targetdiv");
                target.removeClass("btn-info");
                 target.fadeIn();
                 target.removeClass("hidden");
                   target.addClass("btn-danger");
         
                   target.html( "Could not save item:<br/>" + object.responseText );
             
            }',
        ));
    }else
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Save'));
    ?>


    <div class="hidden update_box" id="targetdiv"></div>

    <?php $this->endWidget(); ?>

</div><!-- form -->