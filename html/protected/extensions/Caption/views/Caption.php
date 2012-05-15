<?php
// variables avalaible:
// $caption_name;
 //$controller_description = $caption_data[0];
 //$action_description = $caption_data[1];

$description = $caption_data['description'];
$name = $caption_data['name'];
$action = $caption_data['action'];


if ($description):
    ?>

    <div class="caption_wrapper">
        <div class="caption_header"><?php echo $name; ?></div>
        <div class="controller_description"><?php echo $description; ?></div>
        <div class="action_description"> <?php echo $action; ?> </div>

    </div>


    <?php
endif;
?>
