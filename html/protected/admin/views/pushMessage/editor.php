<?php
$navBarItems = array();

if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this push message?')), '');
}

$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'Push Messages',
    'brandUrl' => array('pushMessage/index'),
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

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'push-message-form',
    'enableAjaxValidation' => false,
        ));
?>
<div class="form-actions">

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div>  

        <?php echo $form->textAreaRow($model, 'alert', array('class' => 'span5', 'cols' => '150', 'rows' => '2')); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'payload_id'); ?>
        <?php
        $payloadList = CHtml::listData(Payload::model()->findAll(), 'id', 'title');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );
        //  echo $form->dropDownList($model, 'payload_id', $payloadList);
        ?>
        <?php echo $form->error($model, 'payload_id'); ?>
    </div>

    <div>
        <?php echo $form->hiddenField($model, 'payload_id'); ?>
        <?php
// ext is a shortcut for application.extensions
        $this->widget('ext.ExtendedAutoComplete.ExtendedAutoComplete', array(
            'name' => 'test_autocomplete',
            'source' => $this->createUrl('payload/findPayload'),
// attribute_value is a custom property that returns the 
// name of our related object -ie return $model->related_model->name
            'value' => $model->isNewRecord ? '' : $model->payload->title,
            'options' => array(
                'minLength' => 1,
                'autoFill' => false,
                'focus' => 'js:function( event, ui ) {
            $( "#test_autocomplete" ).val( ui.item.title );
            return false;
        }',
                'select' => 'js:function( event, ui ) {
            $("#' . CHtml::activeId($model, 'payload_id') . '")
            .val(ui.item.id);
            return false;
        }'
            ),
            'htmlOptions' => array('class' => 'input-1', 'autocomplete' => 'off'),
            'methodChain' => '.data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.title +  "</a>" )
            .appendTo( ul );
    };'
        ));
        
        if(!$model->isNewRecord ){
        //    echo $model->payload->title;
        }
        
        ?>
    </div>

    <div>
        <?php echo $form->textFieldRow($model, 'creation_date', array('class' => 'span2')); ?>
    </div>

    <hr/>

    <h4>Tags:</h4>

    <div class="row-fluid">
        <?php
        $this->widget('ext.TagSelector.TagSelector', array(
            'model' => $model,
            'tag_types' => array('alert', 'district')
        ));
        ?>
    </div>


    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
