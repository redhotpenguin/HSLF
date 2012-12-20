<?php
$this->breadcrumbs = array(
    'Mobile Users' => array('index'),
    $model->_id,
);
?>


<table>

    <tbody class="table">
        <?php
        foreach ($model->fields as $field => $value) {
            ?>
            <tr>
                <td><?php echo $field; ?></td>
                <td><?php
        if (is_array($value)) {
            foreach ($value as $v) {
                echo $v . ',';
            }
        } elseif (is_object($value)) {
            if ($value instanceof MongoId) {
                echo $value->{'$id'};
            } elseif ($value instanceof MongoDate) {
                echo $value->sec;
            } else {
                print_r($value);
            }
        } else {
            echo $value;
        }
            ?></td>


            </tr>

            <?php
        }
        ?>
    </tbody>

</table>




<?php
if (YII_DEBUG):
    echo 'Debug:<pre>';
    print_r($model->fields);
    echo '</pre>';
endif;