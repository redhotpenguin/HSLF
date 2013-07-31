<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/static/mobileuser/mobile_user.js');


$tenant = Yii::app()->user->getLoggedInUserTenant();

$action_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$ns = "var ns  = {action_url: '" . $action_url . "'};";


Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);



$navBarItems = array(
    '',
    array('label' => 'Browse', 'url' => array('browse')),
    '',
    array('label' => 'Export', 'url' => 'export'),
    ''
);


$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');

$this->header = 'Mobile Users';
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";
?>

<div class = "heroUserCount">
    <h2><?php echo CHtml::link(number_format($mobile_user_count), array('mobileUser/index')); ?> users have registered with your app.</h2>
</div>

<div class = "section-divider">
    <h3>filters</h3>
</div>

<div class="form">

    <div id="filters">

        <?php
        echo CHtml::beginForm('/', "POST", array("id" => "mobile_user_form"));
        ?>

        <div class="row">
            <h4>Filter by Tag</h4>         
            <p class="helpText">Users who have any of the following tags</p>
            <?php
            $this->widget('backend.extensions.TagSelector.TagSelector', array(
                'model' => new MobileUser,
                'tag_types' => array('organization', 'alert', 'district'),
                'display_tag_creator' => false,
            ));
            ?>
        </div>

        <div class="row">
            <h4>Filter by Device:</h4>
            <?php
            echo CHtml::dropDownList("device_type", "device_type", array("" => "Any", "ios" => "iOS", "android" => "Android"));
            ?>
        </div>


        <div class="row">
            <h4>Filter by Push </h4>
            <em>Users who have push enabled</em>

            <?php
            echo CHtml::checkBox("push_only", false, array("id" => "push_only_checkbox"));
            ?>

        </div>
    </div>

    <?php
    echo CHtml::endForm();
    ?>

</div>

<div class = "section-divider">
    <h3>Result</h3>
</div>

<div class="heroUserCount">
    <div id="userCountLoader"  style="display:none;">
        <h2>Searching...</h2>
    </div>
    <div id="userCountResult">
        <h2> <span id="mobile_user_count"><?php echo number_format($mobile_user_count); ?></span></h2>
        <b>mobile users found.</b>
        <div class="clearfix>"></div>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('htmlOptions' => array('id' => 'export_btn'), 'buttonType' => 'submit', 'type' => 'info', 'label' => 'Export Current Selection'));
        ?>
    </div>
</div>

