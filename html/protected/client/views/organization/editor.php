<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '');

    $this->headerButton = Chtml::linkButton('Delete', array(
                'class' => 'btn btn-danger',
                'submit' => array('delete', 'id' => $model->id),
                'confirm' => 'Are you sure you want to delete this organization?'
            ));

    $this->introText = 'View and update your Organization’s details. Fields with *asterisks are required. Click "Save" when you are done making changes or adding new content. Be sure to check the Organization in the app after you’ve made updates to ensure changes were made correctly. You may have to wait 30 minutes for the app to refresh before you can see these changes.';
} else {
    $this->introText = 'Fill in the fields on the tabs below to create a new Organization. Fields with *asterisks are required. Click "Save" when you are done making changes or adding new content. Be sure to check the Organization in the app after you’ve made updates to ensure changes were made correctly. You may have to wait 30 minutes for the app to refresh before you can see these changes.';
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Organizations';
$this->secondaryNav['url'] = array('organization/index');

$this->header = $model->isNewRecord ? 'Create Organization' : $model->name;
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




    $tabs = array(
        array('label' => 'Contact Information', 'content' => $infoTab, 'active' => true),
        array('label' => 'Details', 'content' => $detailTab),
    );

    if (Yii::app()->user->canManageContacts()) {
        $contactTab = $this->renderPartial('tabs/_tab_contact', array(
            'model' => $model,
            'form' => $form,
                ), true);


        array_push($tabs, array('label' => 'Contacts', 'content' => $contactTab, 'active' => false));
    }

    if (Yii::app()->user->canManageTags()) {
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
                 target.fadeTo(500, 1.00);
                 target.removeClass("hidden");
                 
                  if ( response == "success" ){
                         target.addClass("btn-success");
                         target.html( "Organization successfully saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save organization." );
                  }
                  
                     $("html, body").animate({ scrollTop: 0 }, 300);

                     target.fadeTo(5000, 0.00, function(){ //fade

        $(this).slideUp(500, function() { //slide up
                 target.removeClass("btn-danger");
                 target.removeClass("btn-success");
        });
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

    <?php
    $this->endWidget();
    ?>

</div><!-- form -->