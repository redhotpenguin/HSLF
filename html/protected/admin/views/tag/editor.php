<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this tag?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Tags',
    'brandUrl' => array('tag/index'),
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
        'id' => 'tag-form',
            //   'enableAjaxValidation' => true,
            // 'stateful' => true,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name', array('size' => 60)); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'type');
        echo $form->dropDownList($model, 'type', $model->getTagTypes());

        echo $form->error($model, 'type');
        ?>
    </div>

    <div class="row buttons">

        <?php
        if (!$model->isNewRecord) {

            $url = CHtml::normalizeUrl(array(
                        'tag/update',
                        'id' => $model->id,
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
                         target.html( "Tag successfully saved" );
                    }
                    else{
                    target.addClass("btn-danger");
                      target.html( "Could not save tag." );
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
         
                   target.html( "Could not save tag:<br/>" + object.responseText );
             
            }',
                    )));
        }else
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save'));
        ?> 


    </div>

    <div class="hidden update_box" id="targetdiv">
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->