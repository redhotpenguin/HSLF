<table  class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>item</th>
            <th>vote</th>
        </tr>
    </thead>
    <?php
    $listed_votes = CHtml::listData(Vote::model()->findAll(), 'id', 'name');
    $listed_votes += array('0' => 'Not set');

    foreach (ScorecardItem::model()->findAllByAttributes(array('office_id'=>$office_id)) as $scorecardItem) {
        echo '<tr>';

        echo '<td>' . $scorecardItem->name . '</td>';

        echo '<td>';

        $scorecard = Scorecard::model()->findByAttributes(array(
            "ballot_item_id" => $model->id,
            "scorecard_item_id" => $scorecardItem->id
                ));

        if ($scorecard) {
            echo CHtml::dropDownList("scorecards[{$scorecard->scorecardItem->id}]", $scorecard->vote->id, $listed_votes);
        } else {
             echo CHtml::dropDownList("scorecards[{$scorecardItem->id}]", 0, $listed_votes);
        }

        echo '</td>';

        echo '</tr>';
    }
    ?>
</table>