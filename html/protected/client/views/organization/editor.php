<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this organization?')), '');
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Organizations';
$this->secondaryNav['url'] = array('organization/index');

$this->header = $model->isNewRecord? 'Organization' : $model->name;
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";



?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'organization-form',
        'enableAjaxValidation' => false,
            ));

    $infoTab = $this->renderPartial('tabs/_tab_info', array(
        'model' => $model,
        'form' => $form,
            ), true);

    $detailTab = $this->renderPartial('tabs/_tab_detail', array(
        'model' => $model,
        'form' => $form,
            ), true);



    $contactTab = $this->renderPartial('tabs/_tab_contact', array(
        'model' => $model,
        'form' => $form,
            ), true);


    $tabs = array(
        array('label' => 'Contact Information', 'content' => $infoTab, 'active' => true),
        array('label' => 'Details', 'content' => $detailTab),
        array('label' => 'Contacts', 'content' => $contactTab),
    );

    if (Yii::app()->user->hasPermission('manageTags')) {
        $tagsTab = $this->renderPartial('tabs/_tab_tags', array(
            'model' => $model,
            'form' => $form,
                ), true);

        array_push($tabs, array('label' => 'Tags', 'content' => $tagsTab));
    }

    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'placement' => 'left',
        'tabs' => $tabs,
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>


    <div class="clearfix"></div>

    <hr/>


    <div class="row buttons">
        <?php
        //$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));


        if (!$model->isNewRecord) {
            $url = CHtml::normalizeUrl(array(
                        'organization/update',
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
                         target.addClass("btn-success");
                         target.html( "Organization successfully saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save organization." );
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
        }else
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => 'Save', 'type' => 'primary'));
        ?>
    </div>
    <div class="hidden update_box" id="targetdiv"></div>

    <?php
    $this->endWidget();
    ?>

</div><!-- form -->