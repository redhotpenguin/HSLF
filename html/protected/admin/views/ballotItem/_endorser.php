<label>Endorsers</label>
<div id="endorsers">
    <table class="table table-bordered table-striped ">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Description</th>
            </tr>
        <thead>
        <tbody class="">
            <?php
            foreach ($endorser_list as $endorser):

                if ($model->hasEndorser($endorser->id))
                    $checked = "checked = checked";
                else {
                    $checked = "";
                }
                ?>
                <tr>
                    <td> 
                        <label class="checkbox">
                            <input type="checkbox" name="endorsers[]" <?php echo $checked; ?> value="<?php echo $endorser->id; ?>">

                            <?php echo $endorser->name; ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo CHtml::link(substr(strip_tags($endorser->description), 0, 100) . '...', Chtml::normalizeUrl(array('endorser/update', 'id' => $endorser->id)), array('target' => '_blank'));
                        ?>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>


