<label>Endorsers</label>
<div id="endorsers">
<?php

foreach ($endorser_list as $endorser):

    if ($model->hasEndorser($endorser->id))
        $checked = "checked = checked";
    else {
        $checked = "";
    }
    ?>
    <input type="checkbox" name="endorsers[]" <?php echo $checked; ?> value="<?php echo $endorser->id; ?>"><b> <?php echo $endorser->name; ?></b>:
    <?php echo substr(strip_tags($endorser->description), 0, 100); ?>
    <br> <br>

    <?php
endforeach;
?>
</div>


