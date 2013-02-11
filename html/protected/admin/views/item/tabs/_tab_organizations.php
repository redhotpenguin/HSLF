<div id="organizations" class="resize-vertical">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Position</th>
            </tr>
        <thead>
        <tbody class="">
            <?php
            
            // move to model:
            $position_list = ItemOrganization::$positions;
            
            foreach ($organization_list as $organization):

                $itemOrganization = ItemOrganization::model()->findByAttributes(array(
                    "item_id" => $model->id,
                    "organization_id" => $organization->id
                        ));
                ?>
                <tr>
                    <td> 
                        <label class="checkbox">

                            <?php echo $organization->name; ?>
                        </label>
                    </td>
               

                    <td>
                        <?php
                        if ($itemOrganization) {
                            echo CHtml::dropDownList("organizations[{$itemOrganization->organization->id}]", $itemOrganization->position, $position_list);
                        } else {
                            echo CHtml::dropDownList("organizations[{$organization->id}]", 0, $position_list);
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


