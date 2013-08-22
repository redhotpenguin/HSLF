<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/static/mobileuser/mobile_user.css');

$navBarItems = array(
    '',
    array('label' => 'Browse', 'url' => array('browse')),
    '',
);
$this->secondaryNav['items'] = $navBarItems;
$this->secondaryNav['name'] = 'Mobile Users';
$this->secondaryNav['url'] = array('mobileUser/index');
?>


<table id="user_attributes">

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
                echo date('l jS \of F Y - h:i:s A (T)', $value->sec);
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