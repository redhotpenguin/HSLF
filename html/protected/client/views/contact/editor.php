<?php
$navBarItems = array();
if (!$model->isNewRecord) {
    array_push($navBarItems, '', array('label' => 'Create', 'url' => array('create'),
            ), '', array('label' => 'Delete', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this contact?')), '');

    $this->introText = 'View and update a Contact’s details. Fields with *asterisks are required. If Email or Phone Number is left blank, the button in the app will not be active. Click "Save" when you are done making changes or adding new content.';
} else {
    $this->introText = 'Fill in the fields below to create a new Contact. Fields with *asterisks are required. If Email or Phone Number is left blank, the button in the app will not be active. Click “Save” when you are done making changes or adding new content.';
}

$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Contacts';
$this->secondaryNav['url'] = array('contact/index');

$this->header = $model->isNewRecord ? 'Contact' : "{$model->first_name} {$model->last_name}";
?>

<div class="form">

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'contact-form',
    'enableAjaxValidation' => false,
        ));

echo $form->errorSummary($model);
?>

    <div class="row-fluid">

        <div class="span6">
    <?php
    echo $form->labelEx($model, 'first_name');
    echo $form->textField($model, 'first_name', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
    echo $form->error($model, 'first_name');
    ?>
        </div>

        <div  class="span6">
            <?php
            echo $form->labelEx($model, 'last_name');
            echo $form->textField($model, 'last_name', array('size' => 60, 'class' => 'span11'));
            echo $form->error($model, 'last_name');
            ?>
        </div>
    </div>

    <div class="row-fluid">

        <div class="span6">
<?php
echo $form->labelEx($model, 'email');
echo $form->textField($model, 'email', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
echo $form->error($model, 'email');
?>
        </div>

        <div  class="span6">
            <?php
            echo $form->labelEx($model, 'title');
            echo $form->textField($model, 'title', array('size' => 60, 'class' => 'span11'));
            echo $form->error($model, 'title');
            ?>
        </div>
    </div>


    <div class="row-fluid">

        <div class="span6">
<?php
echo $form->labelEx($model, 'phone_number');
echo $form->textField($model, 'phone_number', array('size' => 60, 'class' => 'span11', 'maxlength' => 512));
echo $form->error($model, 'phone_number');
?>
        </div>


    </div>

    <div class="row button"> 
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? 'Create' : 'Save',
));
?>
    </div> 

        <?php
        $this->endWidget();
        ?>

</div>