<?php
//var_dump($model->endorsers);
// todo: move to controller
$endorser_list = Endorser::model()->findAll();
?>

<label>Endorsers</label>

<?php
foreach ($endorser_list as $endorser):

    if ($model->hasEndorser($endorser->id))
        $checked = "checked = checked";
    else {
        $checked = "";
    }
    ?>
    <input type="checkbox" name="endorsers[]" <?php echo $checked; ?> value="<?php echo $endorser->id; ?>"> <?php echo $endorser->name; ?> <br>

    <?php
endforeach;

?>


