<?php
$navBarItems = array();

if (!$model->isNewRecord)
    $navBarItems = array(
        '',
        array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item news update?')),
        '',
    );



$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => 'News Update - ' . substr($model->item->item, 0, 25) . '...',
    'brandUrl' => array('item/update', 'id' => $model->item->id, 'activeTab' => 'news'),
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
echo $updated = (isset($updated) ? $updated : "");
?>
<div class="form">


    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'candidate-form'
            ));
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 111, 'class' => 'span9',)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'slug'); ?>
        <?php echo $form->textField($model, 'slug', array('size' => 111, 'class' => 'span9',)); ?>
        <?php echo $form->error($model, 'slug'); ?>
    </div>



    <div class="row">
        <?php
        echo $form->labelEx($model, 'content');
        $this->widget('backend.extensions.tinymce.TinyMce', array(
            'model' => $model,
            'attribute' => 'content',
            'htmlOptions' => array(
                'rows' => 20,
                'cols' => 85,
                'class' => 'span9',
            ),
        ));

        echo $form->error($model, 'content');
        ?>
    </div>



    <div class="row">
        <?php echo $form->labelEx($model, 'excerpt'); ?>
        <?php echo $form->textArea($model, 'excerpt', array('rows' => 5, 'cols' => 85, 'class' => 'span9',)); ?>
        <?php echo $form->error($model, 'excerpt'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'date_published'); ?>
        <?php
        if (empty($model->date_published))
            $model->date_published = date('Y-m-d h:i:s');

        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'ItemNews[date_published]',
            'value' => $model->date_published,
            // additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd ' . date('h:i:s'),
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;float:left;'
            ),
        ));
        ?>
        <?php echo $form->error($model, 'date_published'); ?>
    </div>

    <div style="clear:both;"></div>

    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->