<?php
$className = get_class($model);
$tenant = Yii::app()->user->getLoggedInUserTenant();
$siteUrl = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$contactList = CHtml::listData(Contact::model()->findAll(array('order' => 'first_name ASC')), 'id', function($contact) {
                    return $contact->first_name . " " . $contact->last_name;
                });


$ns = "var contactSelector_ns  = {site_url: '" . $siteUrl . "'};";

Yii::app()->clientScript->registerScript('contactSelector_ns', $ns, CClientScript::POS_HEAD);
?>


<div class="clearfix"></div>

<div class="row-fluid">
    <div class="span11" id="contacts">
        <?php
        if (!empty($contacts))
            foreach ($contacts as $contact) {
                ?>
                <div class="contactRow">
                    <div class='contactName'> 
                        <?php echo $contact->first_name . " " . $contact->last_name; ?>
                    </div>
                    <input type='hidden' name='Organization[contacts][]' value='<?php echo $contact->id; ?>'/> 

                </div>
                <?php
            }
        ?>
    </div>

    <div class="span11">
        <?php
        $options = array(
            'tabindex' => '1',
            'class' => 'span11',
            'name' => 'contacts[]',
            'style' => 'float:left;',
            'id' => 'ContactDropdown'
        );

        echo CHtml::dropDownList('ContactDropdown', '', $contactList, $options);
        ?>

    </div>
</div>

<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array(
    'id' => 'createContactModal',
    'autoOpen' => false,
));
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Create New Contact</h4>
</div>

<div class="modal-body">
    <?php
    $this->render('contact_creator', array('contact' => new Contact()));
    ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'label' => 'Save',
        'url' => '#',
        'htmlOptions' => array(
            'id' => 'save_contact_btn'),
    ));
    ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal', 'id' => 'close_btn'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?><?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Create New Contact',
    'type' => 'info',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#createContactModal',
    ),
));