<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/static/mobileuser/mobile_user.js');


$tenant = Yii::app()->user->getLoggedInUserTenant();

$action_url = Yii::app()->params['site_url'] . '/client/' . $tenant->name;


$ns = "var ns  = {action_url: '" . $action_url . "'};";


Yii::app()->clientScript->registerScript('settings-script', $ns, CClientScript::POS_HEAD);


if ($isAdmin)
    $navBarItems = array(
        '',
        array('label' => 'Browse', 'url' => array('browse')),
        '',
        array('label' => 'Export', 'url' => 'export'),
        ''
    );

else
    $navBarItems = array();



$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');

$this->header = 'Mobile Users';
$this->introText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor blandit tellus eget pharetra. Donec id massa sit amet felis dictum semper. Maecenas sed nisi a magna aliquet dictum. Interdum et malesuada fames ac ante ipsum primis in faucibus";
?>

<div class="form">

    <div id="filters">

        <?php
        echo CHtml::beginForm('/', "POST", array("id" => "mobile_user_form"));
        ?>

        <hr/>


        <h4>Filter by Tag</h4>         
        <div class="row">
            <?php
            $this->widget('backend.extensions.TagSelector.TagSelector', array(
                'model' => new MobileUser,
                'tag_types' => array('organization', 'alert', 'district'),
                'display_tag_creator' => false,
            ));
            ?>
        </div>

        <div class="row">
            <h4>Device Type:</h4>
            <?php
            echo CHtml::dropDownList("device_type", "device_type", array("" => "Any", "ios" => "iOS", "android" => "Android"));
            ?>
        </div>

        <hr/>

        <div class="row">
            <h4>Push only:</h4>
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


<div class="hero-unit">
    <h1> <span id="mobile_user_count"><?php echo number_format($mobile_user_count); ?></span> mobile users</h1>
</div>

