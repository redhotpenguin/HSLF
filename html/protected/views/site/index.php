<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Welcome to the MVG Administration site</h1>


<div style="height:600px;">


    <?php
    $data = array(
        array("id" => 1, "name" => "John",
            "parents" => array(
                array("id" => 10, "name" => "Mary",
                    "parents" => array(
                        array("id" => 100, "name" => "Jane",
                            "parents" => array(
                                array("id" => 1000, "name" => "Helene"),
                                array("id" => 1001, "name" => "Peter")
                            )
                        ),
                        array("id" => 101, "name" => "Richard",
                            "parents" => array(
                                array("id" => 1010, "name" => "Lisa"),
                                array("id" => 1011, "name" => "William")
                            )
                        ),
                    ),
                ),
                array("id" => 11, "name" => "Derek",
                    "parents" => array(
                        array("id" => 110, "name" => "Julia"),
                        array("id" => 111, "name" => "Christian",
                            "parents" => array(
                                array("id" => 1110, "name" => "Deborah"),
                                array("id" => 1111, "name" => "Marc"),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );


echo 'find';

    $this->widget('system.web.widgets.CTreeView', array(
        'data' => $data,
        'id'=>'areas',
        'animated' => 'normal',
        'collapsed' => true,
        'htmlOptions' => array('class' => 'treeview-gray')));
    ?>


</div>