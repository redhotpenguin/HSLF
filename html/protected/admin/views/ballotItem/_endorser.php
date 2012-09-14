<label>Endorsers</label>
<div id="endorsers">
    <table class="table table-bordered table-striped ">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Description</th>
                <th>Position</th>
            </tr>
        <thead>
        <tbody class="">
            <?php
            
            // move to model:
            $position_list = BallotItemEndorser::$positions;
            
            foreach ($endorser_list as $endorser):

                $ballotItemEndorser = BallotItemEndorser::model()->findByAttributes(array(
                    "ballot_item_id" => $model->id,
                    "endorser_id" => $endorser->id
                        ));
                ?>
                <tr>
                    <td> 
                        <label class="checkbox">

                            <?php echo $endorser->name; ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        echo CHtml::link(substr(strip_tags($endorser->description), 0, 100) . '...', Chtml::normalizeUrl(array('endorser/update', 'id' => $endorser->id)), array('target' => '_blank'));
                        ?>
                    </td>

                    <td>
                        <?php
                        if ($ballotItemEndorser) {
                            echo CHtml::dropDownList("endorsers[{$ballotItemEndorser->endorser->id}]", $ballotItemEndorser->position, $position_list);
                        } else {
                            echo CHtml::dropDownList("endorsers[{$endorser->id}]", 0, $position_list);
                        }
                        ?>

                    </td>
                </tr>
    <?php
endforeach;
?>
        </tbody>
    </table>
</div>


