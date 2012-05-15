<?php
// variables avalaible:
// $caption_name;
 $controller_description = $caption_data[0];
 $action_description = $caption_data[1];


if ($controller_description):
    ?>

    <div class="caption_wrapper">
        <div class="caption_header"><?php echo $caption_name; ?></div>
        <div class="controller_description"><?php echo $controller_description; ?></div>
        <div class="action_description"> <?php echo $action_description; ?> </div>

    </div>


    <?php
endif;
?>
